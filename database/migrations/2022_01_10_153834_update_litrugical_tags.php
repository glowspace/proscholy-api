<?php

use App\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLitrugicalTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (($handle = fopen(__DIR__ . '/../tags_new.csv', 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                if ($data[0] == 'id') {
                    continue;
                }

                Tag::updateOrCreate([
                    'id' => $data[0],
                    'name' => $data[1],
                    'description' => $data[2],
                    'type' => $data[3],
                    'parent_tag_id' => $data[4],
                    'created_at' => $data[5],
                    'updated_at' => $data[6],
                    'hide_in_liturgy' => $data[7],
                    'order' => $data[8],
                    'lit_day_identificator' => $data[9]
                ]);
            }
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
