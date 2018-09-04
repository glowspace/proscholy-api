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
			$table->unsignedInteger('author_id');
			$table->unsignedInteger('song_lyrics_id');

			// types: lyrics, music, lyrics_translation, ...
			$table->string('type', 191);
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
