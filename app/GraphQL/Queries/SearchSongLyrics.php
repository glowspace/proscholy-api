<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use App\Songbook;
use App\SongLyric;

class SearchSongLyrics
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if (config('elastic-custom.is-disabled')) {
            // Elastic disabled by .env, return all songs
            // return SongLyric::paginate($args['per_page'], 'page', $args['page']);

            $paginator = (new LengthAwarePaginator(SongLyric::take(40)->get(), max(SongLyric::count(), 40), $args['per_page'], $args['page'], [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]));

            return $paginator;
        }

        $searchParams = json_decode($args['search_params'], true);
        $minScore = 0;
        if(isset($searchParams['min_score'])) {
            $minScore = floatval($searchParams['min_score']);
        }

        $filter_ez = Request::header('Filter-Content') == 'ez';

        // https://github.com/babenkoivan/elastic-scout-driver-plus/blob/master/docs/available-methods.md
        $queryResult = SongLyric::searchQuery($searchParams['query'])
            ->when($filter_ez, function($builder) {
                // fix the problem when ElasticSearch returns different number of hits than actually are scoped by SongLyrics
                // by applying a post-filter that corresponds to the global scope 
                // - issue came from separation of Evangelicky Zpevnik / ZPS

                $cez_songbook_id = Songbook::where('shortcut', 'ez')->first()->id;

                return $builder->postFilter(['terms' => ['songbook_records.songbook_id' => [$cez_songbook_id]]]);           
            })
            ->sortRaw($searchParams['sort'])
            ->load(['songbook_records'])
            ->minScore($minScore)
            ->paginate($args['per_page'], 'page', $args['page'])->onlyModels();

        return $queryResult;
    }
}
