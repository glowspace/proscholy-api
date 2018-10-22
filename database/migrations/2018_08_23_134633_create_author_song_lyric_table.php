<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthorSongLyricTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('author_song_lyric', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('author_id');
			$table->unsignedInteger('song_lyric_id');

			// types: lyrics, music, lyrics_translation, ...
			$table->string('type', 191)->default(0);
		});


		Schema::table('author_song_lyric', function(Blueprint $table)
		{
			$table->foreign('author_id')->references('id')->on('authors')
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
		Schema::drop('author_song_lyric');
	}
}
