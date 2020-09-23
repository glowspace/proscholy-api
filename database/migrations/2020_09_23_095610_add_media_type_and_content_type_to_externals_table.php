<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaTypeAndContentTypeToExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('externals', function (Blueprint $table) {
            // this should be extensible so we use the string type
            // .. we try to guess the media_type based on the link/file type

            // file/docx, file/png, file/... , spotify, youtube/link, youtube/playlist, ... 
            $table->string('media_type')->nullable();


            // this should be fixed - there is not many content types there
            // .. this can be sometimes guessed (music) but i.e. for pdf type cannot (is it sheet music..? is it only lyrics?)
            $table->integer('content_type')->unsigned()->default(0); // music, video, sheet music, ...
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('externals', function (Blueprint $table) {
            $table->dropColumn('media_type');
            $table->dropColumn('content_type');
        });
    }
}
