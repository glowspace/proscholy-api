<?php

use League\Flysystem\FileNotFoundException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// use App\File;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RenamePublicFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $files = DB::table('files')->select(['filename', 'path'])->get();

        // first rename the actual files in storage

        foreach ($files as $f) {
            try {
                Storage::move($f->path, 'public_files/' . $f->filename);
            } catch (FileNotFoundException $exp) {
                Log::error('File not found during migration at path ' . $f->path);
            }
        }

        // then update the db records
        DB::update("update files set path = concat('public_files/', filename)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // nothing necessary to do here
    }
}
