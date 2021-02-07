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
            $output = $this->client->getResultOutputFile($res);
        } else {
            $output = $this->client->getResultLog($res);
        }

        $this->client->deleteResultAsync($res);

        return $output;
    }
}
