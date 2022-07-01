<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondPartsService;

class LilypondPreviewTotal
{
    public function __invoke($rootValue, array $args)
    {
        $lpt_service = app(LilypondPartsService::class);
        $svg = $lpt_service->makeTotalSvgFast(
            $args['lilypond_total']['lilypond_parts'],
            $args['lilypond_total']['global_src'] ?? '',
            $args['lilypond_total']['render_config'] ?? [],
            $args['lilypond_total']['sequence_string'] ?? ''
        );

        return compact('svg');
    }
}
