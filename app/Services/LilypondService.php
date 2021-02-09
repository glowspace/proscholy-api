<?php

namespace App\Services;

use ProScholy\LilypondRenderer\Client;
use ProScholy\LilypondRenderer\LilypondSrc;

class LilypondService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function makeSvg($lilypond, $key_major = null, $crop = true)
    {
        $res = $this->client->renderSvg($this->makeLilypondSource($lilypond, $key_major), $crop);

        if ($res->isSuccessful()) {
            $output = $this->client->getResultOutputFile($res);
        } else {
            $output = $this->client->getResultLog($res);
        }

        $this->client->deleteResultAsync($res);

        return $output;
    }

    public function makeLilypondSource($lilypond, $key_major = null): LilypondSrc
    {
        $src = new LilypondSrc($lilypond);
        $src->applyLayout()->applyInfinitePaper();

        if ($key_major) {
            $src->setOriginalKey($key_major);
        }

        return $src;
    }
}
