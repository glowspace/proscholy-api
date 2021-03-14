<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeHasOneRelationsToNonNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyric_lilypond_svg', function (Blueprint $table) {
            $table->longtext('lilypond_svg')->nullable(false)->change();
        });
        Schema::table('song_lyric_lilypond_src', function (Blueprint $table) {
            $table->longtext('lilypond_src')->nullable(false)->change();
        });
        Schema::table('song_lyric_lyrics', function (Blueprint $table) {
            $table->longtext('lyrics')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('song_lyric_lilypond_svg', function (Blueprint $table) {
            $table->longtext('lilypond_svg')->nullable(true)->change();
        });
        Schema::table('song_lyric_lilypond_src', function (Blueprint $table) {
            $table->longtext('lilypond_src')->nullable(true)->change();
        });
        Schema::table('song_lyric_lyrics', function (Blueprint $table) {
            $table->longtext('lyrics')->nullable(true)->change();
        });
    }
}
