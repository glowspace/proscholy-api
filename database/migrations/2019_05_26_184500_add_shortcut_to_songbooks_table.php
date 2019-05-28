<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShortcutToSongbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songbooks', function (Blueprint $table) {
            $table->string("shortcut", 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('songbooks', function (Blueprint $table) {
            $table->dropColumn("shortcut");
        });
    }
}
