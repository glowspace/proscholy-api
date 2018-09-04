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
			$table->unsignedInteger('song_lyric_id');
			$table->string('number', 20)->nullable();
			$table->string('placeholder', 191);
			$table->timestamps();
			// // $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
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
