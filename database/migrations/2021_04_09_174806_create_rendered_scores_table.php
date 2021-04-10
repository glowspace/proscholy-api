<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenderedScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rendered_scores', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lilypond_parts_sheet_music_id')->nullable();
            $table->unsignedBigInteger('external_id')->nullable();

            $table->json('render_config')->nullable();
            $table->string('filename', 50);
            $table->string('filetype', 6);
            $table->json('secondary_filetypes')->nullable();
            $table->boolean('is_rendered')->default(false);

            $table->timestamps();
        });

        Schema::table('rendered_scores', function (Blueprint $table) {
            $table->index('lilypond_parts_sheet_music_id');
            $table->index('external_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rendered_scores');
    }
}
