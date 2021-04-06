<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondService;

class LilypondPreviewTotal
{
    public function resolve($rootValue, array $args)
    {
        $ly_service = new LilypondService();
        $svg = $ly_service->makeTotalSvgFast(
            $args['lilypond_total']['lilypond_parts'],
            $args['lilypond_total']['global_src'] ?? '',
            $args['lilypond_total']['global_config'] ?? []
        );

        return compact('svg');
    }
}
