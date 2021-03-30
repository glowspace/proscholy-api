<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLilypondPartsSheetMusicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lilypond_parts_sheet_music', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('song_lyric_id')->references('id')->on('song_lyrics');

            $table->json('lilypond_parts');
            $table->text('global_src')->nullable();
            $table->json('global_config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lilypond_parts_sheet_music');
    }
}
