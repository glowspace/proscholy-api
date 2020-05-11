<?php

use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Author::class, 30)->create()->each(function ($author) {
            // $song->song_lyrics()->save(factory(App\SongLyric::class)->make());
        });
    }
}
