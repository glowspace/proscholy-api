<?php

namespace App\Services;

use App\Jobs\RenderLilypondPart;
use App\LilypondPartsSheetMusic;
use ProScholy\LilypondRenderer\Client;
use ProScholy\LilypondRenderer\LilypondBasicTemplate;
use ProScholy\LilypondRenderer\LilypondPartsTemplate;

use ProScholy\LilypondRenderer\LilypondPartsGlobalConfig;

class LilypondPartsSheetMusicService
{
    protected LilypondService $lp_service;
    protected RenderedScoreService $rs_service;

    public function __construct(LilypondService $lp_service, RenderedScoreService $rs_service)
    {
        $this->lp_service = $lp_service;
        $this->rs_service = $rs_service;
    }

    // todo: deprecate
    public function makeTotalSvgMobile(LilypondPartsSheetMusic $lp_sheet_music)
    {
        $score_config = array_merge($lp_sheet_music->score_config, [
            'hide_voices' => ['sopran', 'alt', 'tenor', 'bas', 'zeny', 'muzi']
        ]);

        $data = $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate(
            $lp_sheet_music->lilypond_parts,
            $lp_sheet_music->global_src ?? '',
            $score_config
        ), true);

        return $data['svg'] === '' ? $data['log'] : $data['svg'];
    }

    public function makePartSvgFast($part, $global_src, $score_config_input)
    {
        $data = $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate([$part], $global_src, $score_config_input), false);
        return $data['svg'] === '' ? $data['log'] : $data['svg'];
    }

    public function makeTotalSvgFast($parts, $global_src, $score_config_input)
    {
        $data =  $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate($parts, $global_src, $score_config_input), false);
        return $data['svg'] === '' ? $data['log'] : $data['svg'];
    }

    public function makeLilypondPartsTemplate($parts, ?string $global_src, $render_config_input = []): LilypondPartsTemplate
    {
        $render_config_data = array_merge(
            $this->getDefaultScoreConfigData(true),
            $render_config_input
        );

        $render_config = new LilypondPartsGlobalConfig(
            $render_config_data['version'],
            $render_config_data['two_voices_per_staff'],
            $render_config_data['global_transpose_relative_c'],
            $render_config_data['merge_rests'],
            $render_config_data['hide_bar_numbers'],
            $render_config_data['force_part_breaks'],
            $render_config_data['note_splitting']
        );

        if (count($render_config_data['hide_voices']) > 0) {
            $render_config->setVoicesHidden($render_config_data['hide_voices']);
        }

        if (isset($render_config_data['paper_width_mm'])) {
            $render_config->setCustomPaper($render_config_data['paper_width_mm']);
        }

        $src = new LilypondPartsTemplate($global_src ?? '', $render_config);

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

    public function renderLilypondPartsSheetMusic(LilypondPartsSheetMusic $lpsm, $add_render_configs)
    {
        if (!$lpsm->renderable) {
            logger("LilypondParts ID $lpsm->id is not renderable, deleting all its RenderedScores");
            foreach ($lpsm->rendered_scores() as $score) {
                $this->rs_service->destroyRenderedScore($score);
            }
            return;
        }

        foreach ($add_render_configs as $rc) {
            logger("Dispatching jobs for LilypondParts ID $lpsm->id");
            RenderLilypondPart::dispatch($lpsm->id, $rc);
        }
    }
}
