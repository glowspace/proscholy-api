<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLicenseTypeToSongLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->unsignedTinyInteger('licence_type_cc')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->dropColumn('licence_type_cc');
        });
    }
}
