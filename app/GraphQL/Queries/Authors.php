<?php

namespace App\GraphQL\Queries;

use App\Author;
use DB;

class Authors
{
    public function resolve($rootValue, array $args)
    {
        $query = Author::query();

        if (isset($args['order_last_associated'])) {
            $query->select("authors.*")
                ->leftJoin("author_song_lyric as asl", "asl.author_id", "=", "authors.id")
                ->orderByRaw("MAX(asl.id) desc")
                ->groupBy("authors.id");
        }

        if (isset($args['search_string']))
            return Author::search($args['search_string'])->get();

        if (isset($args['order_abc']))
            $query = $query->orderBy('name', 'asc');

        if (isset($args['type']))
            $query = $query->where('type', $args['type']);

        return $query->get();
    }
}
