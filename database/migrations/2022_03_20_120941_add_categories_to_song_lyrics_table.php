<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoriesToSongLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->boolean('is_for_band')->default(false);
            $table->boolean('is_for_organ')->default(false);
            $table->boolean('is_for_choir')->default(false);
        });

        // apply default TRUE only for newly added songs
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->boolean('is_for_band')->default(true)->change();
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
            $table->dropColumn('is_for_band');
            $table->dropColumn('is_for_organ');
            $table->dropColumn('is_for_choir');
        });
    }
}
