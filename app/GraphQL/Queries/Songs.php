<?php

namespace App\GraphQL\Queries;

use App\Song;

class Songs
{
    public function resolve($rootValue, array $args)
    {
        return Song::whereHas('song_lyrics')->get();
    }
}
