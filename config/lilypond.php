<?php

return [
    'host' => env('LILYPOND_HOST'),
    'port' => env('LILYPOND_PORT'),
    'use_rendered_scores' => env('USE_RENDERED_SCORES', false),
    'rendered_scores_dir' => env('RENDERED_SCORES_DIR', 'rendered_scores'),
    'rendered_scores_zip' => env('RENDERED_SCORES_ZIP', 'rendered_scores.tar.gz')
];
