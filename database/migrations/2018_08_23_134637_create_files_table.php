<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('filename', 191);
			$table->unsignedInteger('song_lyric_id')->nullable();
			$table->unsignedInteger('author_id')->nullable();

			$table->integer('licence_type')->nullable();
			$table->text('licence_content', 65535)->nullable();

			$table->string('description', 191);
			$table->timestamps();
		});

		Schema::table('files', function(Blueprint $table)
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
		Schema::drop('files');
	}

}
