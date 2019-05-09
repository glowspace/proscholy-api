<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Author;

class DeleteDuplicateAuthors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $list = collect();
        $authors_merge = collect();

        // select authors to keep
        foreach (Author::all() as $author) {
            $filtered = $list->filter(function($a) use ($author) {
                return $a->name === $author->name;
            });

            if ($filtered->count() > 0) {
                $a_keep = $filtered->first();
                $authors_merge->push([$a_keep, $author]);
            } else {
                $list->push($author);
            }
        }

        Log::info($authors_merge->toArray());

        foreach ($authors_merge as $arr) {
            $a_keep = $arr[0];
            $a_merge = $arr[1];

            $a_keep->mergeOtherAuthor($a_merge);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
