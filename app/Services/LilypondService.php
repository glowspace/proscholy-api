<?php

namespace App\Services;

use ProScholy\LilypondRenderer\Client;
use ProScholy\LilypondRenderer\LilypondBasicTemplate;
use ProScholy\LilypondRenderer\LilypondPartsTemplate;

use Illuminate\Support\Str;
use ProScholy\LilypondRenderer\LilypondPartsGlobalConfig;

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
        return $this->doClientRenderSvg($this->makeLilypondPartsTemplate([$part], $global_src), false);
    }

    public function makeTotalSvgFast($parts, $global_src, $global_config_input)
    {
        $config = new LilypondPartsGlobalConfig(
            $global_config_input['version'] ?? '2.22.0',
            $global_config_input['two_voices_per_staff'] ?? true,
            $global_config_input['global_transpose_relative_c'] ?? false,
            $global_config_input['merge_rests'] ?? true,
            $global_config_input['hide_bar_numbers'] ?? true,
            $global_config_input['force_part_breaks'] ?? false
        );

        $hide_voices = $global_config_input['hide_voices'] ?? [];

        if (count($hide_voices) > 0) {
            $config->setVoicesHidden($hide_voices);
        }

        return $this->doClientRenderSvg($this->makeLilypondPartsTemplate($parts, $global_src, $config), false);
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

    public function makeLilypondPartsTemplate($parts, $global_src, ?LilypondPartsGlobalConfig $global_config = null): LilypondPartsTemplate
    {
        $src = new LilypondPartsTemplate($global_src, $global_config);

        foreach ($parts as $part) {
            $key_major = $part['key_major'] ?? 'c';
            $time_signature = $part['time_signature'] ?? '4/4';

            $src->withPart(
                $part['name'],
                $part['src'] ?? '',
                $key_major,
                $part['end_key_major'] ?? $key_major,
                $time_signature,
                $part['end_time_signature'] ?? $time_signature
            );
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
