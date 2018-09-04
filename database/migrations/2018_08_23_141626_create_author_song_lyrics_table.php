<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthorSongLyricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('author_song_lyrics', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('author_id')->nullable();
			$table->integer('song_lyrics_id')->nullable();

			// types: lyrics, music, lyrics_translation, ...
			$table->string('type', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('author_song_lyrics');
	}

}
