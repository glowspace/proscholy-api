<?php

namespace App\Jobs;

use App\LilypondPartsSheetMusic;
use App\Services\LilypondPartsSheetMusicService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\SongLyric;
use App\Services\LilypondService;
use App\Services\RenderedScoreService;
use App\Services\SongLyricService;
use App\Song;

class RenderLilypondPart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lpsm_id;
    protected $render_config;
    protected $frontend_display_order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lilypond_parts_sheet_music_id, $render_config, $frontend_display_order)
    {
        $this->lpsm_id = $lilypond_parts_sheet_music_id;
        $this->render_config = $render_config;
        $this->frontend_display_order = $frontend_display_order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LilypondService $lp_service, LilypondPartsSheetMusicService $lpsm_service, RenderedScoreService $rs_service)
    {
        $lpsm = LilypondPartsSheetMusic::find($this->lpsm_id);

        $lp_template = $lpsm_service->makeLilypondPartsTemplate($lpsm->lilypond_parts, $lpsm->global_src, $this->render_config);

        // render the template and get the files' data from lp_service
        $data = $lp_service->doClientRenderSvg($lp_template, true);

        // call rs_service to store the RenderedScore with its data
        $rs_service->createLilypondRenderedScore(
            $lpsm,
            $this->render_config,
            'svg',
            $data['svg'],
            [
                'midi' => $data['midi']
            ],
            $this->frontend_display_order
        );
    }
}
