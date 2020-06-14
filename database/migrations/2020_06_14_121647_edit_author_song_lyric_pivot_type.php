<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAuthorSongLyricPivotType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('author_song_lyric', function (Blueprint $table) {
            $table->integer('type')->default(0)->change();
            $table->renameColumn('type', 'authorship_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('author_song_lyric', function (Blueprint $table) {
            $table->string('authorship_type')->default(0)->change();
            $table->renameColumn('authorship_type', 'type');
        });
    }
}
