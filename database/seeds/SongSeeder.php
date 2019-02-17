<?php

use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Song::class, 3)->create()->each(function ($song) {
            $song->song_lyrics()->save(factory(App\SongLyric::class)->make());
        });
    }
}
