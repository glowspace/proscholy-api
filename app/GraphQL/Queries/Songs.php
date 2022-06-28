<?php

namespace App\GraphQL\Queries;

use App\Song;

class Songs
{
    public function __invoke($rootValue, array $args)
    {
        return Song::whereHas('songLyrics')->get();
    }
}
