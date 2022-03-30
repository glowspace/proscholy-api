<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

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
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
        logger($searchParams);

        // https://github.com/babenkoivan/elastic-scout-driver-plus/blob/master/docs/available-methods.md
        $queryResult = SongLyric::searchQuery($searchParams['query'])
            ->sortRaw($searchParams['sort'])
            ->load(['songbook_records'])
            ->minScore(0.5)
            ->paginate($args['per_page'], 'page', $args['page'])->onlyModels();

        return $queryResult;
    }
}
