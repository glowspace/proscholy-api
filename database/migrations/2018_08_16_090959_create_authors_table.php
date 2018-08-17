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
			$table->integer('id', true);
			$table->integer('type')->nullable();
			$table->string('url', 191)->nullable();
			$table->string('ytchannel', 191)->nullable();
			$table->text('descpription', 65535)->nullable();
			$table->string('email', 191)->nullable();
			$table->text('password', 65535)->nullable();
			$table->string('name', 191)->nullable();
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
