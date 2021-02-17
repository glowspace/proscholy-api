<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\SongLyric;
use App\Services\LilypondService;
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
    public function handle(LilypondService $lily_service)
    {
        $sl = SongLyric::find($this->song_lyric_id);

        $svg = $lily_service->makeSvg($sl->lilypond, $sl->lilypond_key_major);

        if ($svg === false) {
            logger('Unsuccessful Lilypond render');
            return;
        }

        logger('Successful Lilypond render');
        $sl->update(['lilypond_svg' => $svg]);
    }
}
