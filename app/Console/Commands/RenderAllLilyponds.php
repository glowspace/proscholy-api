<?php

namespace App\Console\Commands;

use App\Services\SongLyricLilypondService;
use Illuminate\Console\Command;
use App\SongLyric;

class RenderAllLilyponds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lilypond:render-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forces all SongLyrics with LP to re-render';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(SongLyricLilypondService $sll)
    {
        SongLyric::whereHas('lilypond_src')->orWhereHas('lilypond_parts_sheet_music', function ($q_lp) {
            return $q_lp->renderable();
        })->get()->each(function ($song_lyric) use ($sll) {
            $sll->dispatchRenderJobs($song_lyric, $song_lyric->lilypond_parts_sheet_music);
        });
    }
}
