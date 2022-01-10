<?php

use App\Services\SongLyricModelService;
use App\SongLyric;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongLyricBibleReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_lyric_bible_reference', function (Blueprint $table) {
            $table->unsignedBigInteger('song_lyric_id');
            $table->string('book');
            $table->unsignedSmallInteger('start_chapter');
            $table->unsignedSmallInteger('start_verse');
            $table->unsignedSmallInteger('end_chapter');
            $table->unsignedSmallInteger('end_verse');

            $table->index('book');
            $table->index(['start_chapter', 'start_verse']);
            $table->index(['end_chapter', 'end_verse']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_lyric_bible_reference');
    }
}
