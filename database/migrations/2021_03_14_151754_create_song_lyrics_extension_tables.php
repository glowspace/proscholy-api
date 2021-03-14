<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongLyricsExtensionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_lyric_lilypond_src', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('song_lyric_id');
            $table->longtext('lilypond_src')->nullable();
        });

        Schema::create('song_lyric_lilypond_svg', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('song_lyric_id');
            $table->longtext('lilypond_svg')->nullable();
        });

        Schema::create('song_lyric_lyrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('song_lyric_id');
            $table->longtext('lyrics')->nullable();
        });

        // add indexes

        Schema::table('song_lyric_lilypond_src', function (Blueprint $table) {
            $table->index('song_lyric_id');
            $table->index([DB::raw('lilypond_src(1)')]);
        });
        Schema::table('song_lyric_lilypond_svg', function (Blueprint $table) {
            $table->index('song_lyric_id');
            $table->index([DB::raw('lilypond_svg(1)')]);
        });
        Schema::table('song_lyric_lyrics', function (Blueprint $table) {
            $table->index('song_lyric_id');
            $table->index([DB::raw('lyrics(1)')]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_lyric_lilypond_src');
        Schema::dropIfExists('song_lyric_lilypond_svg');
        Schema::dropIfExists('song_lyric_lyrics');
    }
}
