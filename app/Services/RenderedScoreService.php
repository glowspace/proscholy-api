<?php

namespace App\Services;

use App\Author;
use App\LilypondPartsSheetMusic;
use App\SongLyric;
use App\RenderedScore;

use Illuminate\Support\Facades\Storage;
use Exception;

class RenderedScoreService
{
    protected $dir = '/rendered_scores';

    protected function makeFile($contents, string $extension, ?string $filename = null): string
    {
        $path = Storage::path($this->dir);

        if (!file_exists(Storage::path($path)))
            mkdir(Storage::path($path));

        $filename = isset($filename) ? $filename : uniqid();

        $res = Storage::put($path . "/$filename.$extension", $contents);
        if (!$res) {
            throw new Exception("Storing of the rendered file $filename.$extension was not successful");
        }

        return $filename;
    }

    // public function createLilypondRenderedScore(LilypondPartsSheetMusic $lp, $render_config_data, $files_contents)
    // {
    // }

    // public function FunctionName(Type $var = null)
    // {
    //     # code...
    // }

    public function destroyRenderedScore(RenderedScore $score)
    {
        // delete all associated filetypes
        $extensions = [$score->filetype, ...$score->secondary_filetypes];

        foreach ($extensions as $ext) {
            $fpath = Storage::path($this->dir) . "/$score->filename.$ext";

            if (!Storage::delete($fpath)) {
                logger("File $fpath was not deleted");
            }
        }

        $score->delete();
    }
}
