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
			$table->increments('id');
			$table->string('name', 191);
			$table->unsignedInteger('song_id');

			$table->longtext('lyrics');
			$table->longtext('description');

			$table->boolean('is_authorized');
			$table->boolean('is_original');
			$table->boolean('is_opensong');

			$table->string('lang', 191);

			$table->integer('licence_type')->nullable();
			$table->text('licence_content', 65535)->nullable();
			$table->integer('visits')->nullable();
			$table->timestamps();
		});

		Schema::table('song_lyrics', function(Blueprint $table)
		{
			$table->foreign('song_id')->references('id')->on('songs')
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
		Schema::drop('song_lyrics');
	}

}
