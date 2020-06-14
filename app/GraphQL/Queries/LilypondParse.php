<?php

namespace App\GraphQL\Queries;

use App\Author;
use App\Services\SongLyricService;

class LilypondParse
{
    public function resolve($rootValue, array $args)
    {
        $sl_service = new SongLyricService();

        logger($args);

        $svg = $sl_service->getLilypondSvg($args['lilypond']);
        // $html = "<html>$svg</html>";

        return compact('svg');
    }
}
