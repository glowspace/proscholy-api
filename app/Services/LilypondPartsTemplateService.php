<?php

namespace App\Services;

use App\LilypondPartsSheetMusic;
use ProScholy\LilypondRenderer\Client;
use ProScholy\LilypondRenderer\LilypondBasicTemplate;
use ProScholy\LilypondRenderer\LilypondPartsTemplate;

use ProScholy\LilypondRenderer\LilypondPartsGlobalConfig;

class LilypondPartsTemplateService
{
    protected LilypondService $lp_service;

    public function __construct(LilypondService $lp_service)
    {
        $this->lp_service = $lp_service;
    }

    // todo: deprecate
    public function makeTotalSvgMobile(LilypondPartsSheetMusic $lp_sheet_music)
    {
        $global_config = array_merge($lp_sheet_music->global_config, [
            'hide_voices' => ['sopran', 'alt', 'tenor', 'bas', 'zeny', 'muzi']
        ]);

        return $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate(
            $lp_sheet_music->lilypond_parts,
            $lp_sheet_music->global_src ?? '',
            $global_config
        ), true);
    }

    public function makePartSvgFast($part, $global_src, $global_config_input)
    {
        return $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate([$part], $global_src, $global_config_input), false);
    }

    public function makeTotalSvgFast($parts, $global_src, $global_config_input)
    {
        return $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate($parts, $global_src, $global_config_input), false);
    }

    public function makeLilypondPartsTemplate($parts, $global_src, $global_config_input = []): LilypondPartsTemplate
    {
        $global_config_input_with_defaults = array_merge(
            $this->getDefaultGlobalConfigData(true),
            $global_config_input
        );

        $global_config = new LilypondPartsGlobalConfig(
            $global_config_input_with_defaults['version'],
            $global_config_input_with_defaults['two_voices_per_staff'],
            $global_config_input_with_defaults['global_transpose_relative_c'],
            $global_config_input_with_defaults['merge_rests'],
            $global_config_input_with_defaults['hide_bar_numbers'],
            $global_config_input_with_defaults['force_part_breaks'],
            $global_config_input_with_defaults['note_splitting']
        );

        if (count($global_config_input_with_defaults['hide_voices']) > 0) {
            $global_config->setVoicesHidden($global_config_input_with_defaults['hide_voices']);
        }

        if (isset($global_config_input_with_defaults['paper_width_mm'])) {
            $global_config->setCustomPaper($global_config_input_with_defaults['paper_width_mm']);
        }

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
                $part['end_time_signature'] ?? $time_signature,
                $part['break_before'] ?? false,
                $part['part_transpose'] ?? false
            );
        }

        return $src;
    }

    public function getDefaultGlobalConfigData(bool $include_render_config)
    {
        $arr = [
            'version' => '2.22.0',
            'two_voices_per_staff' => true,
            'merge_rests' => true,
            'note_splitting' => true
        ];

        if ($include_render_config) {
            $arr = array_merge($arr, [
                'global_transpose_relative_c' => false,
                'hide_bar_numbers' => true,
                'force_part_breaks' => false,
                'hide_voices' => []
            ]);
        }

        return $arr;
    }

    public function renderLilypondParts(RenderedScoreService $rs)
    {
    }
}
