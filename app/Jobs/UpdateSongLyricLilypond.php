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
use App\Services\SongLyricService;
use App\Song;

class UpdateSongLyricLilypond implements ShouldQueue
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
    public function handle(LilypondClientService $lily_service, LilypondPartsService $lilyparts_service, SongLyricLilypondService $sl_lily_service)
    {
        $sl = SongLyric::find($this->song_lyric_id);

        $svg = false;

        if ($sl->lilypond_parts_sheet_music()->exists() && !$sl->lilypond_parts_sheet_music->is_empty) {
            // new LP exists, render these
            logger('Rendering the new LP code');
            $svg = $lilyparts_service->makeTotalSvgMobile($sl->lilypond_parts_sheet_music);
        } else if ($sl->lilypond_src()->exists()) {
            logger('Rendering the old LP code');
            $svg = $lily_service->makeSvg($sl->lilypond_src, $sl->lilypond_key_major);
        } else {
            logger("SongLyric ID: $sl->id Lilypond rendering requested but no LP exists");
        }


        if ($svg === false || empty($svg)) {
            logger('Unsuccessful Lilypond render');
            return;
        }

        logger('Successful Lilypond render');
        $sl_lily_service->handleLilypondSvg($sl, $svg);
    }
}
