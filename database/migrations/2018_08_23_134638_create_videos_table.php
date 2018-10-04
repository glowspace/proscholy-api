<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVideosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('videos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('song_lyric_id')->nullable();
			$table->unsignedInteger('author_id')->nullable();
			$table->integer('type');
			$table->integer('visits');
			$table->text('url', 65535)->nullable();
			$table->timestamps();
		});

		Schema::table('videos', function(Blueprint $table)
		{
			$table->foreign('song_lyric_id')->references('id')->on('song_lyrics')
				->onUpdate('cascade')->onDelete('set null');

			$table->foreign('author_id')->references('id')->on('authors')
				->onUpdate('cascade')->onDelete('set null');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('videos');
	}

}
