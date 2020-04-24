<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateSongLyricTagsToUniversalTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->unsignedInteger('tag_id');
            $table->string('taggable_type');
            $table->unsignedInteger('taggable_id');    

            $table->foreign('tag_id')->references('id')->on('tags')
				->onUpdate('cascade')->onDelete('cascade');
        });

        foreach (DB::table('song_lyric_tag')->get() as $relation) {
            DB::table('taggables')->insert([
                'tag_id' => $relation->tag_id,
                'taggable_type' => 'App\SongLyric',
                'taggable_id' => $relation->song_lyric_id
            ]);
        }

        // Schema::dropIfExists('song_lyric_tag');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taggables');

        // Schema::table('song_lyric_tag');
    }
}
