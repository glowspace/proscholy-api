<?php

use App\SongLyric;
use Illuminate\Database\Seeder;

class AuthorSongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $songs_count = \App\Author::count();

        foreach (SongLyric::all() as $song_lyric)
        {
            // Attach every song to 1-2 authors
            $song_lyric->authors()->attach(rand(1, $songs_count));

            if (rand(1, 2) == 1)
            {
                $song_lyric->authors()->attach(rand(1, $songs_count));
            }
        }
    }
}
