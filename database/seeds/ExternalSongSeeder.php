<?php

use App\SongLyric;
use Illuminate\Database\Seeder;

use App\External;

class ExternalSongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (SongLyric::all() as $song_lyric)
        {
            // Attach every song to 1-3 externals
            $song_lyric->externals()->saveMany(
                External::all()->random(rand(1,3))
            );
        }
    }
}
