<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\External;
use App\File;

class AssociateAuthorsToExternalsAndFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (External::all() as $external) {
            if ($external->author_id) {
                DB::table('author_external')->insert([
                    'author_id' => $external->author_id,
                    'external_id' => $external->id
                ]);
            }
        }

        foreach (File::all() as $file) {
            if ($file->author_id) {
                DB::table('author_file')->insert([
                    'author_id' => $file->author_id,
                    'file_id' => $file->id
                ]);
            }
        }

        Schema::table('externals', function(Blueprint $table)
        {
            $table->renameColumn('author_id', 'author_id__obsolete');
        });
        Schema::table('files', function(Blueprint $table)
        {
            $table->renameColumn('author_id', 'author_id__obsolete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('author_external')->truncate();
        DB::table('author_file')->truncate();

        Schema::table('externals', function(Blueprint $table)
        {
            $table->renameColumn('author_id__obsolete', 'author_id');
        });
        Schema::table('files', function(Blueprint $table)
        {
            $table->renameColumn('author_id__obsolete', 'author_id');
        });
    }
}
