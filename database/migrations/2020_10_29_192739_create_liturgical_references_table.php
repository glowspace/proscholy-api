<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiturgicalReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liturgical_references', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('song_lyric_id');
            $table->string('type')->nullable();
            $table->string('cycle', 1)->nullable();
            $table->string('reading')->nullable();
            $table->date('date');
            $table->timestamps();

            // $table->foreign('song_lyric_id')->references('id')->on('song_lyrics')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liturgical_references');
    }
}
