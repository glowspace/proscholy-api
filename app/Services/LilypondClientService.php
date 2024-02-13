<?php

namespace App\Services;

use ProScholy\LilypondRenderer\Client as LilypondClient;
use ProScholy\LilypondRenderer\LilypondBasicTemplate;

class LilypondClientService
{
    protected $client;

    public function __construct()
    {
        $this->client = new LilypondClient();
    }

    public function doClientRenderSvg($src, $crop)
    {
        $timestamp_start = microtime(true);

        $res = $this->client->renderSvg($src, $crop);
        $data = [
            'svg' => $res->isSuccessful() ? $this->client->getResultOutputFile($res) : '',
            'log' => $this->client->getResultLog($res)
        ];
        if ($res->contentsHasFile('score.midi')) {
            $data['midi'] = $this->client->getProcessedFile($res->getTmp(), 'score.midi');
        }
        $this->client->deleteResult($res);

        $timestamp_end = microtime(true);
        logger(sprintf('LP render; %s; %s; %s', $crop ? 'normal' : 'fast', config('lilypond_renderer.host'), $timestamp_end - $timestamp_start));

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

    // kind-of-beta support for rendering from XML (via Verovio)
    public function doClientRenderSvgFromXml(string $src)
    {  
        $res = $this->client->renderXml($src);
        $data = [
            'svg' => $res->isSuccessful() ? $this->client->getResultOutputFile($res) : '',
            'log' => $this->client->getResultLog($res)
        ];
        $this->client->deleteResult($res);

        logger('Verovio MusicXML render, ' . config('lilypond_renderer.host'));

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
