<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSequenceStringToLilypondPartsSheetMusicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lilypond_parts_sheet_music', function (Blueprint $table) {
            $table->string('sequence_string')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lilypond_parts_sheet_music', function (Blueprint $table) {
            $table->dropColumn('sequence_string');
        });
    }
}
