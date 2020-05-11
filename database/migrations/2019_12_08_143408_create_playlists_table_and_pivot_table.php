<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistsTableAndPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('public_user_id');
            $table->string('name', 200);
            $table->boolean('is_private')->default(true);
            $table->timestamps();

            $table->foreign('public_user_id')->references('id')->on('public_users');
        });

        Schema::create('playlist_song_lyric', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('playlist_id');
            $table->unsignedInteger('song_lyric_id');
            $table->unsignedInteger('order');
            
            $table->foreign('playlist_id')->references('id')->on('playlists')
                ->onUpdate('cascade')->onDelete('cascade');
    
            $table->foreign('song_lyric_id')->references('id')->on('song_lyrics')
                ->onUpdate('cascade')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlist_song_lyric');
        Schema::dropIfExists('playlists');
    }
}
