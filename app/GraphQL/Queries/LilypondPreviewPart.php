<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondPartsTemplateService;

class LilypondPreviewPart
{
    public function resolve($rootValue, array $args)
    {
        $lpt_service = app(LilypondPartsTemplateService::class);
        $svg = $lpt_service->makePartSvgFast($args['lilypond_part'], $args['global_src'] ?? '', $args['score_config'] ?? []);

        return compact('svg');
    }
}
