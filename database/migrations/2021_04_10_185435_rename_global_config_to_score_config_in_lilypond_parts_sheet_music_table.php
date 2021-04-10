<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameGlobalConfigToScoreConfigInLilypondPartsSheetMusicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lilypond_parts_sheet_music', function (Blueprint $table) {
            $table->renameColumn('global_config', 'score_config');
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
            $table->renameColumn('score_config', 'global_config');
        });
    }
}
