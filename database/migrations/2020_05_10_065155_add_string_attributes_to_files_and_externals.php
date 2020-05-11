<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStringAttributesToFilesAndExternals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('catalog_number', 100)->nullable();
            $table->string('copyright', 100)->nullable();
            $table->string('editor', 100)->nullable();
            $table->string('published_by', 100)->nullable();
        });

        Schema::table('externals', function (Blueprint $table) {
            $table->string('catalog_number', 100)->nullable();
            $table->string('copyright', 100)->nullable();
            $table->string('editor', 100)->nullable();
            $table->string('published_by', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('catalog_number');
            $table->dropColumn('copyright');
            $table->dropColumn('editor');
            $table->dropColumn('published_by');
        });

        Schema::table('externals', function (Blueprint $table) {
            $table->dropColumn('catalog_number');
            $table->dropColumn('copyright');
            $table->dropColumn('editor');
            $table->dropColumn('published_by');
        });
    }
}
