<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('menu_id')->unsigned()->nullable()->index('menu_items_menu_id_foreign');
			$table->string('title', 191);
			$table->string('url', 191);
			$table->string('target', 191)->default('_self');
			$table->string('icon_class', 191)->nullable();
			$table->string('color', 191)->nullable();
			$table->integer('parent_id')->nullable();
			$table->integer('order');
			$table->timestamps();
			$table->string('route', 191)->nullable();
			$table->text('parameters', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('menu_items');
	}

}
