<?php

namespace App\Jobs;

use App\LilypondPartsSheetMusic;
use App\Services\LilypondPartsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\SongLyricLilypondService;

class RenderLilypondScore implements ShouldQueue
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
    public function handle(SongLyricLilypondService $sll_service)
    {
        $lpsm = LilypondPartsSheetMusic::find($this->lpsm_id);

        $sll_service->renderAndUpdateLilypondScoreNew($lpsm, $this->add_render_config, $this->frontend_display_order);
    }
}
