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

        DB::table('song_lyrics')
            ->where('is_original', 0)
            ->where('is_authorized', 0)
            ->update(['type' => DB::raw(1)]);

        DB::table('song_lyrics')
            ->where('is_original', 0)
            ->where('is_authorized', 1)
            ->update(['type' => DB::raw(2)]);

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

        // this is the default behaviour
        // DB::table('song_lyrics')
        //     ->where('type', 0)
        //     ->update([
        //         'is_original' => DB::raw(1),
        //         'is_authorized' => DB::raw(0));

        DB::table('song_lyrics')
            ->where('type', 1)
            ->update([
                'is_original' => DB::raw(0),
                'is_authorized' => DB::raw(0)]);

        DB::table('song_lyrics')
            ->where('type', 2)
            ->update([
                'is_original' => DB::raw(0),
                'is_authorized' => DB::raw(1)]);
        
    
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
