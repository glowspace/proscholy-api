<?php

namespace App\Services;

use App\Author;
use App\LilypondPartsSheetMusic;
use App\SongLyric;
use App\RenderedScore;

class RenderedScoreService
{
    public function createLilypondRenderedScore(LilypondPartsSheetMusic $lp, $config_data, $files_contents)
    {
    }

    public function deleteRenderedScore(RenderedScore $score)
    {
        // delete all associated filetypes
    }
}
