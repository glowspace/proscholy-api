<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\SongLyric;
use App\Author;

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
        if ($args["search_string"] == "") {
            return SongLyric::orderBy("name", "asc")->get();
        }

        // one letter => show only songs starting with this letter
        // if(strlen($args["search_string"]) == 1 || $args["search_string"] == "ch" || $args["search_string"] == "Ch") {
        //     return SongLyric::where('name', 'like', $args["search_string"].'%')->orderBy("name", "asc")->get();
        // }

        $query = SongLyric::search($args['search_string']);

        // $query = SongLyric::searchRaw([
        //     'query' => [
        //         'bool' => [
        //             'must' => [
        //                 'match' => [
        //                     '_all' => 'Brazil'
        //                 ]
        //             ]
        //         ]
        //     ]
        // ]);

        // language: where in
        // 


        $query->limit = 50;

        $query->with('songbook_records');

        return $query->get();
    }
}
