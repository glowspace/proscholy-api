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
			$table->integer('songbook_id')->nullable();
			$table->integer('song_lyric_id')->nullable();
			$table->string('number', 20)->nullable();
			$table->string('placeholder', 191)->nullable();
			$table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
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
