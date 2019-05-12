<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\SongLyric;

class MergeOriginalAndAuthorizedToTypeInSongLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->unsignedInteger('type')->default(0)->after('description');
        });

        foreach (SongLyric::select(['is_original', 'type', 'is_authorized', 'id'])->get() as $song_lyric) {
            if ($song_lyric->is_original) {
                $song_lyric->type = 0;
            } else {
                if ($song_lyric->is_authorized) {
                    $song_lyric->type = 2;
                } else {
                    $song_lyric->type = 1;
                }
            }
            $song_lyric->save();
        }

        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->dropColumn('is_original');
            $table->dropColumn('is_authorized');
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
            $table->boolean('is_authorized')->default(0)->after('type');
            $table->boolean('is_original')->default(1)->after('is_authorized');
        });
        
        foreach (SongLyric::select(['is_original', 'type', 'is_authorized', 'id'])->get() as $song_lyric) {
            if ($song_lyric->type == 0) {
                $song_lyric->is_original = true;
                $song_lyric->is_authorized = false;
            } else {
                if ($song_lyric->type == 2) {
                    $song_lyric->is_original = false;
                    $song_lyric->is_authorized = true;
                } else {
                    $song_lyric->is_original = false;
                    $song_lyric->is_authorized = false;
                }
            }

            $song_lyric->save();
        }
        
    
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
