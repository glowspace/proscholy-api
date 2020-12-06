<?php

use Illuminate\Database\Seeder;
use App\Song;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Song::factory()->count(100)->hasSongLyrics(1)->create();
    }
}
