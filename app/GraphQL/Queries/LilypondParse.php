<?php

namespace App\GraphQL\Queries;

use App\Author;
use App\Services\LilypondService;

class LilypondParse
{
    public function resolve($rootValue, array $args)
    {
        $ly_service = new LilypondService();
        $svg = $ly_service->makeSvg($args['lilypond'], false);

        return compact('svg');
    }
}
