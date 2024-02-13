<?php

namespace App\Services;

use App\Author;
use App\External;
use App\LilypondPartsSheetMusic;
use App\SongLyric;
use App\RenderedScore;

use Illuminate\Support\Facades\Storage;
use Exception;

class RenderedScoreService
{
    protected $dir;

    public function __construct()
    {
        $this->dir = config('lilypond.rendered_scores_dir');
    }

    public function makeFile($contents, string $extension, ?string $filename = null): string
    {
        $path = Storage::path($this->dir);

        if (!file_exists($path))
            mkdir($path);

        $filename = isset($filename) ? $filename : uniqid();

        $res = Storage::put($this->dir . "/$filename.$extension", $contents);
        if (!$res) {
            throw new Exception("Storing of the rendered file $filename.$extension was not successful");
        }

        return $filename;
    }

    public function deleteFile(string $filename, string $extension): bool
    {
        $fpath = $this->dir . "/$filename.$extension";
        $res = Storage::delete($fpath);

        if (!$res) {
            logger("File $fpath was not deleted");
        }

        return $res;
    }

    public function getRenderConfigHash($render_config_data)
    {
        ksort($render_config_data);
        foreach ($render_config_data as &$val) {
            if (is_array($val)) {
                sort($val);
            }
        }

        return hash('crc32', json_encode($render_config_data));
    }

    public function createLilypondRenderedScore(LilypondPartsSheetMusic $lp, $render_config_data, string $primary_filetype, $data, array $secondary_filetypes_data, ?int $frontend_display_order)
    {
        $primary_filename = $this->makeFile($data, $primary_filetype);

        // create secondary files with the same name as primary_filename but different extensions
        foreach ($secondary_filetypes_data as $extension => $contents) {
            $this->makeFile($contents, $extension, $primary_filename);
        }

        // and save the new one into db
        $rs = new RenderedScore([
            'render_config' => $render_config_data,
            'render_config_hash' => $this->getRenderConfigHash($render_config_data),
            'filename' => $primary_filename,
            'filetype' => $primary_filetype,
            'secondary_filetypes' => array_keys($secondary_filetypes_data),
            'frontend_display_order' => $frontend_display_order
        ]);

        logger("Saving rendered score ($primary_filename.$primary_filetype)");

        $lp->rendered_scores()->save($rs);
    }

    public function createMusicXmlRenderedScore(External $external, string $data) {
        $primary_filetype = 'svg';
        $primary_filename = $this->makeFile($data, $primary_filetype);

        // and save the new one into db
        $rs = new RenderedScore([
            // currently the musicxml rendering provides no way to configure the render output
            // (it is hardcoded in the lilypond server renderer)
            'render_config' => [],
            'render_config_hash' => $this->getRenderConfigHash([]),
            'filename' => $primary_filename,
            'filetype' => $primary_filetype,
            'secondary_filetypes' => array_keys([]),
            'frontend_display_order' => null
        ]);

        $external->rendered_scores()->save($rs);
    }

    // todo: call this on soft deleting SongLyric
    public function destroyRenderedScore(RenderedScore $score)
    {
        logger("Deleting rendered score ID $score->id");

        // delete all associated filetypes
        $extensions = [$score->filetype, ...$score->secondary_filetypes];

        foreach ($extensions as $ext) {
            $this->deleteFile($score->filename, $ext);
        }

        $score->delete();
    }
}
