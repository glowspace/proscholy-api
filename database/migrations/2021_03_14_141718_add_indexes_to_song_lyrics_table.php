<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToSongLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->index('deleted_at');
            $table->index('has_chords');
            $table->index([DB::raw('lyrics(1)')]);
            $table->index([DB::raw('lilypond(1)')]);
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
            $table->dropIndex(['deleted_at']);
            $table->dropIndex(['has_chords']);
            $table->dropIndex([DB::raw('lyrics(1)')]);
            $table->dropIndex([DB::raw('lilypond(1)')]);
        });
    }
}
