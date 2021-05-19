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

        $data = [
            'svg' => $res->isSuccessful() ? $this->client->getResultOutputFile($res) : '',
            'log' => $this->client->getResultLog($res)
        ];

        if ($res->contentsHasFile('score.midi')) {
            $data['midi'] = $this->client->getProcessedFile($res->getTmp(), 'score.midi');
        }

        $this->client->deleteResult($res);

        return $data;
    }

    public function doClientRenderPdf($src)
    {
        $res = $this->client->render($src, 'pdf');

        $data = [
            'pdf' => $res->isSuccessful() ? $this->client->getResultOutputFile($res) : '',
            'log' => $this->client->getResultLog($res)
        ];

        if ($res->contentsHasFile('score.midi')) {
            $data['midi'] = $this->client->getProcessedFile($res->getTmp(), 'score.midi');
        }

        $this->client->deleteResult($res);

        return $data;
    }

    public function makeSvgFast($lilypond, $key_major = null)
    {
        $data = $this->doClientRenderSvg($this->makeLilypondBasicTemplate($lilypond, $key_major), false);
        return $data['svg'] === '' ? $data['log'] : $data['svg'];
    }

    public function makeSvg($lilypond, $key_major = null)
    {
        $data = $this->doClientRenderSvg($this->makeLilypondBasicTemplate($lilypond, $key_major), true);
        return $data['svg'] === '' ? $data['log'] : $data['svg'];
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
