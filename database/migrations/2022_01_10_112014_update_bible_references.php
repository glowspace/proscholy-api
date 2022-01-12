<?php

use App\Services\SongLyricModelService;
use App\SongLyric;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBibleReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var SongLyric */
        foreach (SongLyric::all() as $sl) {
            /** @var SongLyricModelService */
            $service = app(SongLyricModelService::class);
            $service->handleBibleReferences($sl, $sl->bible_refs_osis);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
