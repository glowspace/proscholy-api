<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorVisits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->unsignedInteger('visits')->default(0)->change();
        });

        DB::table('song_lyrics')
            ->where('visits', null)
            ->update(['visits' => DB::raw(0)]);

        Schema::table('song_lyrics', function (Blueprint $table) {
            $table->unsignedInteger('visits')->default(0)->nullable(false)->change();
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
            $table->integer('visits')->nullable(true)->change();
        });

        Schema::table('authors', function (Blueprint $table) {
            // no not kidding
            // the type of author.visits had been a string!
            // WHAT MOTHERFUCKER DID THAT? 
            // yup that's right, it was me.. :D
            // had a typo in one migration (add_default_to_author_visits) sry
            $table->string('visits')->default(0)->change();
        });
    }
}
