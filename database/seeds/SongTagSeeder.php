<?php

use Illuminate\Database\Seeder;
use App\Tag;
use App\SongLyric;

class SongTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Tag::$song_lyric_types as $sl_type) {
            foreach (SongLyric::all()->random(20) as $song_lyric) {
                $song_lyric->tags()->sync(
                    Tag::whereType($sl_type)->get()->random(2)
                );
            }
        }
    }
}
