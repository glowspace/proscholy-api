<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSongbookRecordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('songbook_records', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('songbook_id');
			$table->unsignedInteger('song_lyric_id')->nullable();
			$table->string('number', 20)->nullable();
			$table->string('placeholder', 191);
			$table->timestamps();
			// // $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});

		Schema::table('songbook_records', function(Blueprint $table)
		{
			$table->foreign('songbook_id')->references('id')->on('songbooks')
				->onUpdate('cascade')->onDelete('cascade');

			// when the song is deleted, rather stay in database and let placeholder be used
			$table->foreign('song_lyric_id')->references('id')->on('song_lyrics')
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
		Schema::drop('songbook_records');
	}

}
