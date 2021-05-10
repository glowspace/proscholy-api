<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondPartsService;

class LilypondPreviewTotal
{
    public function resolve($rootValue, array $args)
    {
        $lpt_service = app(LilypondPartsService::class);
        $svg = $lpt_service->makeTotalSvgFast(
            $args['lilypond_total']['lilypond_parts'],
            $args['lilypond_total']['global_src'] ?? '',
            $args['lilypond_total']['render_config'] ?? []
        );

        return compact('svg');
    }
}
