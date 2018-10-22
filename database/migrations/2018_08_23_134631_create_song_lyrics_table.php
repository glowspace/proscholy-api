<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSongLyricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('song_lyrics', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->unsignedInteger('song_id');

			$table->longtext('lyrics')->nullable();
			$table->longtext('description')->nullable();

			$table->boolean('is_authorized')->default(0);
			$table->boolean('is_original')->default(0);
			$table->boolean('is_opensong')->default(0);

			$table->string('lang', 191);

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
