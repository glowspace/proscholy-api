<?php

namespace App\Jobs;

use App\LilypondPartsSheetMusic;
use App\Services\LilypondPartsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\LilypondService;
use App\Services\RenderedScoreService;
use Illuminate\Support\Arr;

class RenderLilypondPart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lpsm_id;
    protected $add_render_config;
    protected $frontend_display_order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lilypond_parts_sheet_music_id, $add_render_config, $frontend_display_order)
    {
        $this->lpsm_id = $lilypond_parts_sheet_music_id;
        $this->add_render_config = $add_render_config;
        $this->frontend_display_order = $frontend_display_order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LilypondService $lp_service, LilypondPartsService $lpsm_service, RenderedScoreService $rs_service)
    {
        $lpsm = LilypondPartsSheetMusic::find($this->lpsm_id);

        $final_render_config = array_merge(
            $lpsm->score_config,
            $this->add_render_config
        );

        $lp_template = $lpsm_service->makeLilypondPartsTemplate($lpsm->lilypond_parts, $lpsm->global_src, $final_render_config, $lpsm->sequence_string);

        $filetype = Arr::has($this->add_render_config, 'paper_type') ? 'pdf' : 'svg';

        logger("Dispatched score render with config:");
        logger($this->add_render_config);

        // render the template and get the files' data from lp_service
        $data = $filetype === 'pdf' ? $lp_service->doClientRenderPdf($lp_template) : $lp_service->doClientRenderSvg($lp_template, true);

        // call rs_service to store the RenderedScore with its data
        $rs_service->createLilypondRenderedScore(
            $lpsm,
            $this->add_render_config,
            $filetype,
            $data[$filetype],
            [
                'midi' => $data['midi']
            ],
            $this->frontend_display_order
        );
    }
}
