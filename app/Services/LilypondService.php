<?php

namespace App\Services;

use ProScholy\LilypondRenderer\Client as LilypondClient;
use ProScholy\LilypondRenderer\LilypondBasicTemplate;

class LilypondService
{
    protected $client;

    public function __construct()
    {
        $this->client = new LilypondClient();
    }

    public function doClientRenderSvg($src, $crop)
    {
        $res = $this->client->renderSvg($src, $crop);

        if ($res->isSuccessful()) {
            $output = $this->client->getResultOutputFile($res);
        } else {
            $output = $this->client->getResultLog($res);
        }

        $this->client->deleteResultAsync($res);

        return $output;
    }

    public function makeSvgFast($lilypond, $key_major = null)
    {
        return $this->doClientRenderSvg($this->makeLilypondBasicTemplate($lilypond, $key_major), false);
    }

    public function makeSvg($lilypond, $key_major = null)
    {
        return $this->doClientRenderSvg($this->makeLilypondBasicTemplate($lilypond, $key_major), true);
    }

    public function makeLilypondBasicTemplate($lilypond, $key_major = null): LilypondBasicTemplate
    {
        $src = new LilypondBasicTemplate($lilypond);
        $src->applyDefaultLayout('amiri', 2.5, 'amiri', 3)->applyInfinitePaper()->applyTinynotes();

        if ($key_major) {
            $src->setOriginalKey($key_major);
        }

        return $src;
    }
}
