<?php

namespace App\Services;

use ProScholy\LilypondRenderer\Client;
use ProScholy\LilypondRenderer\LilypondBasicTemplate;
use ProScholy\LilypondRenderer\LilypondPartsTemplate;

use Illuminate\Support\Str;

class LilypondService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    private function doClientRenderSvg($src, $crop)
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

    public function makePartSvgFast($part, $global_src)
    {
        return $this->doClientRenderSvg($this->makeLilypondPartsTamplate([$part], $global_src), false);
    }

    public function makeTotalSvgFast($parts, $global_src)
    {
        return $this->doClientRenderSvg($this->makeLilypondPartsTamplate($parts, $global_src), false);
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

    public function makeLilypondPartsTamplate($parts, $global_src): LilypondPartsTemplate
    {
        $src = new LilypondPartsTemplate($global_src);

        foreach ($parts as $part) {
            $src->withPart($part['name'], $part['src'], $part['key_major'] ?? 'c', $part['time_signature'] ?? '4/4');
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
