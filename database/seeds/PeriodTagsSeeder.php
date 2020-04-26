<?php

use Illuminate\Database\Seeder;

class PeriodTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\Tag::class, 10)->create()->each(function ($author) {
        //     // $song->song_lyrics()->save(factory(App\SongLyric::class)->make());
        // });

        $names = ['Renesance', 'Baroko', 'Romantismus'];

        foreach ($names as $name) {
            App\Tag::create([
                'name' => $name,
                'type' => 10
            ]);
        }
    }
}
