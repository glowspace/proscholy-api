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

    public function makeSvg($lilypond, $crop = true)
    {
        $res = $this->client->renderSvg(LilypondSrc::withLayout($lilypond, true), $crop);

        if ($res->isSuccessful()) {
            $svg = $this->client->getResultOutputFile($res);
            return $svg;
        } else {
            $log = $this->client->getResultLog($res);
            return $log;
        }
    }
}
