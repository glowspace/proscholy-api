<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteOldSongLyricColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->dropIndex([DB::raw('lyrics(1)')]);
            $table->dropIndex([DB::raw('lilypond(1)')]);
        });

        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->dropColumn('lyrics');
            $table->dropColumn('lilypond');
            $table->dropColumn('lilypond_svg');
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
            $table->longtext('lyrics')->nullable();
            $table->longtext('lilypond')->nullable();
            $table->longtext('lilypond_svg')->nullable();
        });

        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->index([DB::raw('lyrics(1)')]);
            $table->index([DB::raw('lilypond(1)')]);
        });
    }
}
