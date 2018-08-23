<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('key', 191)->unique();
			$table->string('display_name', 191);
			$table->text('value', 65535)->nullable();
			$table->text('details', 65535)->nullable();
			$table->string('type', 191);
			$table->integer('order')->default(1);
			$table->string('group', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
