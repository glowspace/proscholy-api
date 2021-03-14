<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSongLyricTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('song_lyric_tag');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('song_lyric_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('song_lyric_id');
            $table->timestamps();
        });
    }
}
