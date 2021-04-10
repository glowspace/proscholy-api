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
        $score_config = array_merge($lp_sheet_music->score_config, [
            'hide_voices' => ['sopran', 'alt', 'tenor', 'bas', 'zeny', 'muzi']
        ]);

        return $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate(
            $lp_sheet_music->lilypond_parts,
            $lp_sheet_music->global_src ?? '',
            $score_config
        ), true);
    }

    public function makePartSvgFast($part, $global_src, $score_config_input)
    {
        return $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate([$part], $global_src, $score_config_input), false);
    }

    public function makeTotalSvgFast($parts, $global_src, $score_config_input)
    {
        return $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate($parts, $global_src, $score_config_input), false);
    }

    public function makeLilypondPartsTemplate($parts, $global_src, $score_config_input = []): LilypondPartsTemplate
    {
        $score_config_input_with_defaults = array_merge(
            $this->getDefaultScoreConfigData(true),
            $score_config_input
        );

        $score_config = new LilypondPartsGlobalConfig(
            $score_config_input_with_defaults['version'],
            $score_config_input_with_defaults['two_voices_per_staff'],
            $score_config_input_with_defaults['global_transpose_relative_c'],
            $score_config_input_with_defaults['merge_rests'],
            $score_config_input_with_defaults['hide_bar_numbers'],
            $score_config_input_with_defaults['force_part_breaks'],
            $score_config_input_with_defaults['note_splitting']
        );

        if (count($score_config_input_with_defaults['hide_voices']) > 0) {
            $score_config->setVoicesHidden($score_config_input_with_defaults['hide_voices']);
        }

        if (isset($score_config_input_with_defaults['paper_width_mm'])) {
            $score_config->setCustomPaper($score_config_input_with_defaults['paper_width_mm']);
        }

        $src = new LilypondPartsTemplate($global_src, $score_config);

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

    public function getDefaultScoreConfigData(bool $include_render_config)
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
