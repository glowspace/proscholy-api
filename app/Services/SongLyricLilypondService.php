<?php

namespace App\Services;

use App\SongLyric;
use App\Jobs\RenderOldSongLyricLilypond;
use App\LilypondPartsSheetMusic;
use App\Services\SongLyricModelService;
use Illuminate\Support\Arr;

class SongLyricLilypondService
{
    protected LilypondClientService $ly_service;
    protected LilypondPartsService $lpsm_service;
    protected RenderedScoreService $rs_service;
    protected SongLyricModelService $sl_rep;

    public function __construct(LilypondClientService $ly_service, LilypondPartsService $lpsm_service, RenderedScoreService $rs_service, SongLyricModelService $sl_rep)
    {
        $this->ly_service = $ly_service;
        $this->lpsm_service = $lpsm_service;
        $this->rs_service = $rs_service;
        $this->sl_rep = $sl_rep;
    }

    // is called by the RenderLilyPondScore job
    public function renderAndUpdateLilypondScoreNew(LilypondPartsSheetMusic $lpsm, array $add_render_config, $frontend_display_order)
    {
        $final_render_config = array_merge(
            $lpsm->score_config,
            $add_render_config
        );

        $lp_template = $this->lpsm_service->makeLilypondPartsTemplate($lpsm->lilypond_parts, $lpsm->global_src, $final_render_config, $lpsm->sequence_string);

        $filetype = Arr::has($add_render_config, 'paper_type') ? 'pdf' : 'svg';

        logger("Dispatched score render with config:");
        logger($add_render_config);

        // render the template and get the files' data from ly_service
        $data = $filetype === 'pdf' ? $this->ly_service->doClientRenderPdf($lp_template) : $this->ly_service->doClientRenderSvg($lp_template, true);

        // call rs_service to store the RenderedScore with its data
        $this->rs_service->createLilypondRenderedScore(
            $lpsm,
            $add_render_config,
            $filetype,
            $data[$filetype],
            [
                'midi' => $data['midi']
            ],
            $frontend_display_order
        );
    }

    // to be deprecated in the future
    public function renderAndUpdateLilyPondScoreOld(SongLyric $sl)
    {
        $svg = false;

        if ($sl->lilypond_parts_sheet_music()->exists() && !$sl->lilypond_parts_sheet_music->is_empty) {
            // new LP exists, render these
            logger('Rendering the new LP code (old database entry)');
            $svg = $this->lpsm_service->makeTotalSvgMobile($sl->lilypond_parts_sheet_music);
        } elseif ($sl->lilypond_src()->exists()) {
            logger('Rendering the old LP code (old database entry)');
            $svg = $this->ly_service->makeSvg($sl->lilypond_src, $sl->lilypond_key_major);
        } else {
            logger("SongLyric ID: $sl->id Lilypond rendering requested but no LP exists");
        }


        if ($svg === false || empty($svg)) {
            logger('Unsuccessful Lilypond render (old database entry)');
            return;
        }

        logger('Successful Lilypond render (old database entry)');
        $this->sl_rep->handleLilypondSvg($sl, $svg);
    }

    public function dispatchRenderJobs($sl_id, LilypondPartsSheetMusic $sheet_parts_music)
    {
        logger("Doing LilyPond render (first the old way)");

        // todo: remove in favour of serving svg files
        RenderOldSongLyricLilypond::dispatch($sl_id);

        // new way of rendering
        // needs to be allowed in .env with USE_RENDERED_SCORES=true
        if (config('lilypond.use_rendered_scores')) {
            logger("New way of rendering LP is enabled, dispatching render score jobs");

            $this->lpsm_service->dispatchRenderScoreJobs($sheet_parts_music);
        }
    }

    // todo: remove lilypond_input and lilypond_key_major (from the old form)
    public function handleLilypondOnUpdate(SongLyric $song_lyric, $lilypond_input, $lilypond_key_major, array $lilypond_parts_sheet_music)
    {
        $new_lilypond_updated =
            $song_lyric->lilypond_parts_sheet_music->lilypond_parts != $lilypond_parts_sheet_music['lilypond_parts'] ||
            $song_lyric->lilypond_parts_sheet_music->score_config != $lilypond_parts_sheet_music['score_config'] ||
            $song_lyric->lilypond_parts_sheet_music->global_src != $lilypond_parts_sheet_music['global_src'] ||
            $song_lyric->lilypond_parts_sheet_music->sequence_string != $lilypond_parts_sheet_music['sequence_string'];

        
        if (isset($lilypond_input)) {
            $this->sl_rep->handleLilypondSrc($song_lyric, $lilypond_input);
        }
        $lpsm = $this->sl_rep->handleLilypondPartsSheetMusic($song_lyric, $lilypond_parts_sheet_music);

        if ($new_lilypond_updated) {
            logger("New lilypond updated");
            $this->dispatchRenderJobs($song_lyric->id, $lpsm);
        }
    }
}
