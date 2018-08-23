<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthorSongTranslationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('author_song_translation', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('author_id')->nullable();
			$table->integer('song_translation_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('author_song_translation');
	}

}
