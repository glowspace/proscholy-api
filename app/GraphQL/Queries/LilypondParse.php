<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondClientService;

class LilypondParse
{
    public function resolve($rootValue, array $args)
    {
        $ly_service = new LilypondClientService();
        $svg = $ly_service->makeSvgFast($args['lilypond'], $args['lilypond_key_major'] ?? null);

        return compact('svg');
    }
}
