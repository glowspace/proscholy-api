<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteNullHasOneRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('song_lyric_lilypond_svg')->whereNull('lilypond_svg')->delete();
        DB::table('song_lyric_lilypond_src')->whereNull('lilypond_src')->delete();
        DB::table('song_lyric_lyrics')->whereNull('lyrics')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
