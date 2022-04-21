<?php

namespace App\GraphQL\Queries;

use App\Author;
use DB;
use ElasticScoutDriverPlus\Support\Query;

class Authors
{
    public function createElasticQuery($str)
    {
        return [
            'bool' => [
                'should' => [
                    ['multi_match' => [
                        'query' => $str,
                        'type' => 'bool_prefix'
                        ]],
                    ['multi_match' => [
                        'query' => $str,
                        'fields' => ['name'],
                        'fuzziness' => 'AUTO'
                    ]]
                ]
            ]
        ];
    }

    public function resolve($rootValue, array $args)
    {
        $query = Author::query();

        if (isset($args['order_last_associated'])) {
            $query->select("authors.*")
                ->leftJoin("author_song_lyric as asl", "asl.author_id", "=", "authors.id")
                ->orderByRaw("MAX(asl.id) desc")
                ->groupBy("authors.id");
        }

        if (isset($args['search_string'])) {
            return Author::searchQuery($this->createElasticQuery($args['search_string']))->execute()->models();
        }

        if (isset($args['order_abc']))
            $query = $query->orderBy('name', 'asc');

        if (isset($args['type']))
            $query = $query->where('type', $args['type']);

        return $query->get();
    }
}
