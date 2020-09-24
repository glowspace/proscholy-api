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
            DB::table('externals')->insert([
                'song_lyric_id' => $f->song_lyric_id,
                'type' => $this->convertFileTypeToExternalType($f->type),
                'created_at' => $f->created_at,
                'updated_at' => $f->updated_at,
                'has_anonymous_author' => $f->has_anonymous_author,
                'is_uploaded' => true,
                'caption' => $f->name,
                'url' => url("soubor/$f->filename")
            ]);
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
        DB::table('externals')->where('is_uploaded', true)->delete();
    }
}
