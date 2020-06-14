<?php

namespace App\GraphQL\Queries;

use App\Author;
use App\Services\SongLyricService;

class LilypondParse
{
    public function resolve($rootValue, array $args)
    {
        $sl_service = new SongLyricService();

        $svg = $sl_service->getLilypondSvg($args['lilypond']);

        return compact('svg');
    }
}
