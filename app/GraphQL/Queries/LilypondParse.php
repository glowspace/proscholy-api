<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondService;

class LilypondParse
{
    public function resolve($rootValue, array $args)
    {
        $ly_service = new LilypondService();
        $svg = $ly_service->makeSvg($args['lilypond'], $args['lilypond_key_major'] ?? null, false);

        return compact('svg');
    }
}
