<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondPartsSheetMusicService;

class LilypondPreviewTotal
{
    public function resolve($rootValue, array $args)
    {
        $lpt_service = app(LilypondPartsSheetMusicService::class);
        $svg = $lpt_service->makeTotalSvgFast(
            $args['lilypond_total']['lilypond_parts'],
            $args['lilypond_total']['global_src'] ?? '',
            $args['lilypond_total']['score_config'] ?? []
        );

        return compact('svg');
    }
}
