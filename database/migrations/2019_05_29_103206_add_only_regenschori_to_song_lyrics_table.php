<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnlyRegenschoriToSongLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->boolean("only_regenschori")->default(false);
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
            $table->dropColumn("only_regenschori");
        });
    }
}
