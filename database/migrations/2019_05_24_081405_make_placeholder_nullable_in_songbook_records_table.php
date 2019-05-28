<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakePlaceholderNullableInSongbookRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songbook_records', function (Blueprint $table) {
            $table->string("placeholder")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('songbook_records', function (Blueprint $table) {
            $table->string('placeholder')->nullable(false)->change();
        });
    }
}
