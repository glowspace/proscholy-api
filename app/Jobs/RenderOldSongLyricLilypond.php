<?php

namespace App\Jobs;

use App\Services\LilypondPartsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\SongLyric;
use App\Services\LilypondClientService;
use App\Services\SongLyricLilypondService;
use App\Services\SongLyricModelService;
use App\Scopes\ExcludeEvangelicalOnlySongsScope;
use App\Song;

class RenderOldSongLyricLilypond implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $song_lyric_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sl_id)
    {
        $this->song_lyric_id = $sl_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LilypondClientService $lily_service, LilypondPartsService $lilyparts_service, SongLyricModelService $sl_service)
    {
        $sl = SongLyric::withoutGlobalScope(ExcludeEvangelicalOnlySongsScope::class)->find($this->song_lyric_id);

        // logger("Old LP render for SL id", $sl->id);

        $svg = false;

        if ($sl->lilypond_parts_sheet_music()->exists() && !$sl->lilypond_parts_sheet_music->is_empty) {
            // new LP exists, render these
            logger('Rendering the new LP code (old database entry)');
            $svg = $lilyparts_service->makeTotalSvgMobile($sl->lilypond_parts_sheet_music);
        } elseif ($sl->lilypond_src()->exists()) {
            logger('Rendering the old LP code (old database entry)');
            $svg = $lily_service->makeSvg($sl->lilypond_src, $sl->lilypond_key_major);
        } else {
            logger("SongLyric ID: $sl->id Lilypond rendering requested but no LP exists");
        }


        if ($svg === false || empty($svg)) {
            logger('Unsuccessful Lilypond render (old database entry)');
            return;
        }

        logger('Successful Lilypond render (old database entry)');
        $sl_service->handleLilypondSvg($sl, $svg);
    }
}
