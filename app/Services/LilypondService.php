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
        $src->applyLayout('default_layout', 'amiri', 2.5, 'amiri', 3)->applyInfinitePaper();

        if ($key_major) {
            $src->setOriginalKey($key_major);
        }

        return $src;
    }

    public function needsLilypondUpdate($lilypond): bool
    {
        $lp_no_spaces = str_replace(' ', '', $lilypond);

        return (!Str::contains($lp_no_spaces, ['melodie={']) &&
            !Str::contains($lp_no_spaces, ['text=\lyricmode{']) &&
            Str::contains($lp_no_spaces, 'indent=0') &&
            Str::contains($lp_no_spaces, 'tagline=""'));
    }
}
