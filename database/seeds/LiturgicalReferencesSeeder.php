<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class LiturgicalReferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\LiturgicalReference::create([
            'type' => '1. čtení',
            'cycle' => 'A',
            'reading' => '1 Král 8,22-23.27-30',
            'date' => Carbon::now()->addDays(2),
            'song_lyric_id' => 1
        ]);

        App\LiturgicalReference::create([
            'type' => 'Evangelium',
            'cycle' => 'A',
            'reading' => 'Mt 13,47-52  (naznačené ze společných textů)',
            'date' => Carbon::now()->addDays(2),
            'song_lyric_id' => 2
        ]);

        App\LiturgicalReference::create([
            'type' => 'Žalm',
            'reading' => 'Žl 132,1-2.3-5.11.12.13-14',
            'date' => Carbon::now()->addDays(4),
            'song_lyric_id' => 3
        ]);
    }
}
