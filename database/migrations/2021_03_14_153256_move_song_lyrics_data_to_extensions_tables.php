<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveSongLyricsDataToExtensionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (DB::table('song_lyrics')->select(['id', 'lyrics', 'lilypond_svg', 'lilypond'])->get() as $song_lyric_data) {
            DB::table('song_lyric_lilypond_svg')->insert([
                'song_lyric_id' => $song_lyric_data->id,
                'lilypond_svg' => $song_lyric_data->lilypond_svg
            ]);

            DB::table('song_lyric_lilypond_src')->insert([
                'song_lyric_id' => $song_lyric_data->id,
                'lilypond_src' => $song_lyric_data->lilypond
            ]);

            DB::table('song_lyric_lyrics')->insert([
                'song_lyric_id' => $song_lyric_data->id,
                'lyrics' => $song_lyric_data->lyrics
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('song_lyric_lilypond_svg')->truncate();
        DB::table('song_lyric_lilypond_src')->truncate();
        DB::table('song_lyric_lyrics')->truncate();
    }
}
