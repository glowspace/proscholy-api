<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLiturgyApprovalStatusToSongLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->unsignedSmallInteger('liturgy_approval_status')->default(0);
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
            $table->dropColumn('liturgy_approval_status');
        });
    }
}
