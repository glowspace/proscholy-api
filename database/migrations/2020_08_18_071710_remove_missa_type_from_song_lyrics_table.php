<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMissaTypeFromSongLyricsTable extends Migration
{
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->dropColumn('missa_type');
        });
    }

    public function down()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->unsignedSmallInteger('missa_type')->default(0);
        });
    }
}
