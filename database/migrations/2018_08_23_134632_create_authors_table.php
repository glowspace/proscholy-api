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
			// TODO: generate url from something later
			$table->string('url', 191)->nullable();
			// TODO: one to many author links
			$table->string('ytchannel', 191);
			$table->text('description', 65535);
			// ?? rather to bind with an existing user account
			$table->text('password', 65535)->nullable();
			// TODO: rather string type..? enum..?
			$table->integer('type')->nullable()->default(0);
			$table->integer('visits')->nullable()->default(0);
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
