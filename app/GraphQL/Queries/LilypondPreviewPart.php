<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondPartsSheetMusicService;

class LilypondPreviewPart
{
    public function resolve($rootValue, array $args)
    {
        $lpt_service = app(LilypondPartsSheetMusicService::class);
        $svg = $lpt_service->makePartSvgFast($args['lilypond_part'], $args['global_src'] ?? '', $args['render_config'] ?? []);

        return compact('svg');
    }
}
