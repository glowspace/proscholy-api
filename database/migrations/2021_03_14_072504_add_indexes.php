<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->index('updated_at');
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->index('taggable_type');
            $table->index('taggable_id');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->index('type');
        });

        Schema::table('liturgical_year_readings', function (Blueprint $table) {
            $table->index('date');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->index('type');
        });

        Schema::table('externals', function (Blueprint $table) {
            $table->index('media_type');
            $table->index('content_type');
            $table->index('is_uploaded');
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
            $table->dropIndex(['updated_at']);
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->dropIndex(['taggable_type']);
            $table->dropIndex(['taggable_id']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex(['type']);
        });

        Schema::table('liturgical_year_readings', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->dropIndex(['type']);
        });

        Schema::table('externals', function (Blueprint $table) {
            $table->dropIndex(['media_type']);
            $table->dropIndex(['content_type']);
            $table->dropIndex(['is_uploaded']);
        });
    }
}
