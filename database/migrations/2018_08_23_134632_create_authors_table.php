<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('authors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('email', 191)->nullable();
			// remove url?
			$table->string('url', 191)->nullable();
			// deleted
			$table->string('ytchannel', 191)->nullable();
			$table->text('description', 65535)->nullable();
			// deleted
			$table->text('password', 65535)->nullable();
			// TODO: rather string type..? enum..?
			$table->integer('type')->nullable()->default(0);
			// set default
			$table->integer('visits');
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
		Schema::drop('authors');
	}

}
