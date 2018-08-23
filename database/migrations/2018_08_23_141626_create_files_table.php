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
			$table->string('filename', 191)->nullable();
			$table->integer('song_translation_id')->nullable();
			$table->integer('author_id')->nullable();
			$table->integer('licence_type')->nullable();
			$table->text('licence_content', 65535)->nullable();
			$table->string('decription', 191)->nullable();
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
		Schema::drop('files');
	}

}
