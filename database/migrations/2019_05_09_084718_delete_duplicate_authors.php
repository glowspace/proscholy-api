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

            $this->mergeOtherAuthor($a_keep, $a_merge);
        }
    }

    // only one time helper function
    public function mergeOtherAuthor(Author $a_keep, Author $a_merge)
    {
        Log::info("Merging author $a_merge->id to $a_keep->id");

        foreach ($a_merge->externals as $model) {
            $model->authors()->detach($a_merge);
            $model->authors()->attach($a_keep);
            $model->save();

            Log::info("associated external: $model->id");
        }
        foreach ($a_merge->files as $model) {
            $model->authors()->detach($a_merge);
            $model->authors()->attach($a_keep);
            $model->save();

            Log::info("associated file: $model->id");
        }
        foreach ($a_merge->songLyrics as $model) {
            $model->authors()->detach($a_merge);
            $model->authors()->attach($a_keep);
            $model->save();

            Log::info("associated song_lyric: $model->id");
        }

        // double check 
        $count = $a_merge->externals()->count() + $a_merge->files()->count() + $a_merge->songLyrics()->count();

        if ($count > 0) {
            Log::error($a_merge);
            return false;
        }

        $a_merge->delete();
        return true;
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
