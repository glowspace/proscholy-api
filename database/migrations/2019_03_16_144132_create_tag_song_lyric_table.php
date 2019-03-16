<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagSongLyricTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_song_lyric', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('song_lyric_id');
            $table->timestamps();
        });

		Schema::table('tag_song_lyric', function(Blueprint $table)
		{
			$table->foreign('tag_id')->references('id')->on('tags')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('song_lyric_id')->references('id')->on('song_lyrics')
                ->onUpdate('cascade')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_song_lyric');
    }
}
