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
        $src = new LilypondSrc($lilypond);
        $src->applyLayout()->applyInfinitePaper();

        if ($key_major) {
            $src->setOriginalKey($key_major);
        }

        $res = $this->client->renderSvg($src, $crop);

        if ($res->isSuccessful()) {
            $output = $this->client->getResultOutputFile($res);
        } else {
            $output = $this->client->getResultLog($res);
        }

        $this->client->deleteResultAsync($res);

        return $output;
    }
}
