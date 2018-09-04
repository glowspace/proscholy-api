<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthorMembershipTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('author_membership', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('is_member_of');
			$table->unsignedInteger('author_id');
		});

		Schema::table('author_membership', function(Blueprint $table)
		{
			$table->foreign('is_member_of')->references('id')->on('authors')
				->onUpdate('cascade')->onDelete('cascade');

			$table->foreign('author_id')->references('id')->on('authors')
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
		Schema::drop('author_membership');
	}

}
