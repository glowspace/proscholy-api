<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_file', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('author_id');
			$table->unsignedInteger('file_id');
		});


		Schema::table('author_file', function(Blueprint $table)
		{
			$table->foreign('author_id')->references('id')->on('authors')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('file_id')->references('id')->on('files')
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
        Schema::drop('author_file');
    }
}
