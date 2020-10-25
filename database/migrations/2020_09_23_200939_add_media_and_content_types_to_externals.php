<?php

use App\Helpers\ExternalMediaLink;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class AddMediaAndContentTypesToExternals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $externals = DB::table('externals')->select(['url', 'type', 'id'])->whereNotNull('url')->get();

        foreach ($externals as $e) {
            $media_link = new ExternalMediaLink($e->url);

            DB::table('externals')->where('id', $e->id)->update([
                'media_type' => $media_link->getExternalMediaType(),
                'content_type' => $media_link->getExternalContentType()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update('update externals set media_type = null, content_type = 0');
    }
}
