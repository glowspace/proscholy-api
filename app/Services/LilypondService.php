<?php

namespace App\Services;

use ProScholy\LilypondRenderer\Client;
use ProScholy\LilypondRenderer\LilypondSrc;

use Illuminate\Support\Str;

class LilypondService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function makeSvgFast($lilypond, $key_major = null)
    {
        $res = $this->client->renderSvg($this->makeLilypondSource($lilypond, $key_major), false);

        if ($res->isSuccessful()) {
            $output = $this->client->getResultOutputFile($res);
        } else {
            $output = $this->client->getResultLog($res);
        }

        $this->client->deleteResultAsync($res);

        return $output;
    }

    public function makeSvg($lilypond, $key_major = null)
    {
        $res = $this->client->renderSvg($this->makeLilypondSource($lilypond, $key_major), true);

        if ($res->isSuccessful()) {
            $output = $this->client->getResultOutputFile($res);
        } else {
            $output = false;
        }

        $this->client->deleteResultAsync($res);

        return $output;
    }

    public function makeLilypondSource($lilypond, $key_major = null): LilypondSrc
    {
        $src = new LilypondSrc($lilypond);
        $src->applyLayout('default_layout', 'amiri', 2.5, 'amiri', 3)->applyInfinitePaper();

        if ($key_major) {
            $src->setOriginalKey($key_major);
        }

        return $src;
    }

    public function needsLilypondUpdate($lilypond): bool
    {
        $lp_no_spaces = str_replace(' ', '', $lilypond);

        if (!Str::contains($lp_no_spaces, 'melodie=')) {
            return true;
        }

        if (Str::contains($lp_no_spaces, 'indent=0') || Str::contains($lp_no_spaces, 'tagline=""')) {
            return true;
        }

        return false;
    }
}
