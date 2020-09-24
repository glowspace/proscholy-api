<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class MigrateFilesToExternals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $files = DB::table('files')->select(['*'])->get();

        foreach ($files as $f) {
            $ex_id = DB::table('externals')->insertGetId([
                'song_lyric_id' => $f->song_lyric_id,
                'type' => $this->convertFileTypeToExternalType($f->type),
                'created_at' => $f->created_at,
                'updated_at' => $f->updated_at,
                'has_anonymous_author' => $f->has_anonymous_author,
                'is_uploaded' => true,
                'caption' => $f->name,
                'url' => url("soubor/$f->filename")
            ]);

            foreach (DB::table('author_file')->select(['author_id'])->where('file_id', $f->id)->get() as $author_file_pivot) {
                DB::table('author_external')->insert([
                    'author_id' => $author_file_pivot->author_id,
                    'external_id' => $ex_id
                ]);
            }

            foreach (DB::table('taggables')->select('tag_id')->where([
                'taggable_type' => 'App\File',
                'taggable_id' => $f->id
            ])->get() as $taggable_pivot) {
                DB::table('taggables')->insert([
                    'taggable_type' => 'App\External',
                    'taggable_id' => $ex_id,
                    'tag_id' => $taggable_pivot->tag_id
                ]);
            }
        }
    }

    private function convertFileTypeToExternalType($type)
    {
        $converter = [
            0 => 0,
            1 => 8,
            2 => 9,
            3 => 4,
            4 => 7
        ];

        return $converter[$type];
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('taggables')->leftJoin('externals', 'taggable_id', '=', 'externals.id')->where([
            'is_uploaded' => true,
            'taggable_type' => 'App\External'
        ])->delete();

        // author_external pivot deleting is handled by the db foreign key `cascade` constraint

        DB::table('externals')->where('is_uploaded', true)->delete();
    }
}
