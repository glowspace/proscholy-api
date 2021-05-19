<?php

namespace App\Services;

use App\Jobs\RenderLilypondPart;
use App\LilypondPartsSheetMusic;
use ProScholy\LilypondRenderer\LilypondPartsTemplate;

use ProScholy\LilypondRenderer\LilypondPartsRenderConfig;

class LilypondPartsService
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
            // 'hide_voices' => ['sopran', 'alt', 'tenor', 'bas', 'zeny', 'muzi']
        ]);

        $data = $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate(
            $lp_sheet_music->lilypond_parts,
            $lp_sheet_music->global_src ?? '',
            $score_config,
            $lp_sheet_music->sequence_string
        ), true);

        return $data['svg'];
    }

    public function makePartSvgFast($part, $global_src, $score_config_input)
    {
        $data = $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate([$part], $global_src, $score_config_input), false);
        return $data['svg'] === '' ? $data['log'] : $data['svg'];
    }

    public function makeTotalSvgFast($parts, $global_src, $score_config_input, $sequence_string)
    {
        $data =  $this->lp_service->doClientRenderSvg($this->makeLilypondPartsTemplate($parts, $global_src, $score_config_input, $sequence_string), false);
        return $data['svg'] === '' ? $data['log'] : $data['svg'];
    }

    public function makeLilypondPartsTemplate($parts, ?string $global_src, $render_config_input = [], ?string $sequence_string = null): LilypondPartsTemplate
    {
        $render_config = new LilypondPartsRenderConfig($render_config_input);
        $src = new LilypondPartsTemplate($global_src ?? '', $render_config);

        $part_names_tokens = array_map(function ($part) { return $part['name']; }, $parts);

        if (empty($sequence_string)) {
            $sequence = $part_names_tokens;
        } else {
            // $sequence_string = "1 1 R .";
            $sequence_string = str_replace("\n", ' BREAK ', $sequence_string);
            $sequence_string = str_replace('.', ' . ', $sequence_string);
            $sequence_string = str_replace('|', ' | ', $sequence_string);
    
            $sequence = explode(' ', $sequence_string);
        }

        $special_tokens = [
            '.' => '\bar "|."', 
            '|' => '\bar "||"', 
            'BREAK' => '\break'
        ];

        foreach ($sequence as $token) {
            if (!empty($token)) {
                // special token => use preconfigured array
                if (array_key_exists($token, $special_tokens)) {
                    $src->withInlineCode($special_tokens[$token]);
                } else {
                    // the token is a part name, retrieve the part from the part list
                    $part = $parts[array_search($token, $part_names_tokens)];
                    $src->withPart(
                        $part['name'],
                        $part['src'] ?? '',
                        $part['key_major'] ?? 'c',
                        $part['time_signature'] ?? '4/4',
                        $part['part_transpose'] ?? false
                    );
                }
            }
        }

        return $src;
    }

    public function getDefaultScoreConfigData()
    {
        $tp = new LilypondPartsRenderConfig();
        
        // filter the default config values for only score_config
        return array_intersect_key(
            $tp->getDefaultConfigData(),
            array_flip(['version', 'two_voices_per_staff', 'merge_rests', 'note_splitting'])
        );
    }

    public function renderLilypondPartsSheetMusic(LilypondPartsSheetMusic $lpsm)
    {
        logger("Deleting old RenderedScores for LP sheet music $lpsm->id");
        foreach ($lpsm->rendered_scores as $score) {
            $this->rs_service->destroyRenderedScore($score);
        }

        foreach ($this->getLilypondPartsRenderData($lpsm) as $data) {
            logger("Dispatching jobs for LilypondParts ID $lpsm->id");
            RenderLilypondPart::dispatch($lpsm->id, $data['render_config'], $data['frontend_display_order'] ?? null);
        }
    }

    protected function getLilypondPartsRenderData(LilypondPartsSheetMusic $lpsm): array
    {
        $men_voices = ['muzi', 'tenor', 'bas'];
        $women_voices = ['zeny', 'sopran', 'alt'];

        $render_data = [];

        // solo + optional SATB choir
        if ($lpsm->hasAnyVoice('solo')) {
            $render_data[] = [
                'render_config' => ['hide_voices' => [...$men_voices, ...$women_voices]],
                'frontend_display_order' => 0
            ];

            if ($lpsm->hasAnyVoice($women_voices)) {
                $render_data[] = [
                    'render_config' => ['hide_voices' => [...$men_voices, 'akordy']],
                    'frontend_display_order' => 1
                ];
            }

            if ($lpsm->hasAnyVoice($men_voices)) {
                $render_data[] = [
                    'render_config' => ['hide_voices' => [...$women_voices, 'akordy']],
                    'frontend_display_order' => 2
                ];
            }
        } else {
            // only SATB template

            // two voices per staff, do one joint file
            if ($lpsm->score_config['two_voices_per_staff']) {
                $render_data[] = [
                    'render_config' => [],
                    'frontend_display_order' => 0
                ];
            } else {
                $render_data = [
                    [
                        'render_config' => ['hide_voices' => [...$men_voices, 'akordy']],
                        'frontend_display_order' => 1
                    ],
                    [
                        'render_config' => ['hide_voices' => [...$women_voices, 'akordy']],
                        'frontend_display_order' => 2
                    ]
                ];
            }
        }

        $render_data[] = [
            'render_config' => ['paper_width_mm' => 240],
            'frontend_display_order' => 3
        ];

        // setting the paper type causes to render PDF
        $render_data[] = [
            'render_config' => ['paper_type' => 'a4']
        ];


        return $render_data;
    }
}
