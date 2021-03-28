<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondService;

class LilypondPreviewPart
{
    public function resolve($rootValue, array $args)
    {
        $ly_service = new LilypondService();
        $svg = $ly_service->makePartSvgFast($args['lilypond_part'], $args['global_src'] ?? '');

        return compact('svg');
    }
}
