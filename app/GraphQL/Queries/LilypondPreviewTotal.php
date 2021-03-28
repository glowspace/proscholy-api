<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondService;

class LilypondPreviewTotal
{
    public function resolve($rootValue, array $args)
    {
        $ly_service = new LilypondService();
        $svg = $ly_service->makeTotalSvgFast($args['lilypond_total']['parts'], $args['lilypond_total']['global_src'] ?? '');


        return compact('svg');
    }
}
