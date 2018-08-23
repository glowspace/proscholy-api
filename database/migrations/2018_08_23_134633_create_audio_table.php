<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAudioTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audio', function(Blueprint $table)
		{
			$table->integer('type')->nullable();
			$table->integer('song_translation_id')->nullable();
			$table->integer('licence_type')->nullable();
			$table->text('licence_content', 65535)->nullable();
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
		Schema::drop('audio');
	}

}
