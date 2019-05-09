<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorExternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_external', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('author_id');
			$table->unsignedInteger('external_id');
		});


		Schema::table('author_external', function(Blueprint $table)
		{
			$table->foreign('author_id')->references('id')->on('authors')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('external_id')->references('id')->on('externals')
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
        Schema::drop('author_external');
    }
}
