<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSongLyricssTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('song_lyrics', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 191)->nullable();
			$table->boolean('is_authorized')->nullable();
			$table->boolean('is_original')->nullable();
			$table->text('description', 65535)->nullable();
			$table->text('lyrics', 65535)->nullable();
			$table->boolean('is_opensong')->nullable();
			// TODO: some lang code instead of id
			$table->integer('lang_id')->nullable();
			$table->integer('song_id')->nullable();
			$table->integer('licence_type')->nullable();
			$table->text('licence_content', 65535)->nullable();
			$table->integer('visits')->nullable();
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
		Schema::drop('song_lyrics');
	}

}
