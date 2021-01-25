<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class AddStatsFieldsToSongLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->unsignedInteger('revision_n_tags')->default(0);
            $table->unsignedInteger('revision_n_authors')->default(0);
            $table->unsignedInteger('revision_n_songbook_records')->default(0);
        });

        DB::table('song_lyrics')->update([
            'revision_n_tags' => DB::raw('(SELECT count(*)
                FROM taggables where taggable_type = "App\\\SongLyric" and taggable_id = song_lyrics.id)'),

            'revision_n_authors' => DB::raw('(SELECT count(*)
                FROM author_song_lyric where song_lyric_id = song_lyrics.id)'),

            'revision_n_songbook_records' => DB::raw('(SELECT count(*)
                FROM songbook_records where song_lyric_id = song_lyrics.id)'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->dropColumn('revision_n_tags');
            $table->dropColumn('revision_n_authors');
            $table->dropColumn('revision_n_songbook_records');
        });
    }
}
