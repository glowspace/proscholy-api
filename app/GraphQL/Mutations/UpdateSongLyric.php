<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;
use App\SongLyric;
use App\Song;
use App\Tag;
use App\Songbook;
use function Safe\array_combine;

class UpdateSongLyric
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        Log::info($args["input"]);
        $input = $args["input"];

        $song_lyric = SongLyric::find($input["id"]);

        // TODO as an event
        if ($input["name"] !== $song_lyric->name) {
            // to be domestic means to have a same name as the parent song
            // this invariant needs to be preserved in order to stay domestic
            if ($song_lyric->isDomestic()) {
                $song_lyric->song->update([
                    'name' => $input["name"]
                ]);
            }
        }

        $song_lyric->update($input);

        // HANDLE AUTHORS
        if (isset($input["authors"]["sync"]))
            $song_lyric->authors()->sync($input["authors"]["sync"]);
        if (isset($input["authors"]["create"])) {
            foreach ($input["authors"]["create"] as $author) {
                $song_lyric->authors()->create(['name' => $author["name"]]);
            }
        }
        $song_lyric->save();

        $tagsToSync = [];

        // HANDLE TAGS - sync
        if (isset($input["tags_unofficial"]["sync"])) {
            $tagsToSync = $input["tags_unofficial"]["sync"];
            // unofficial tags can have parent tags, so add them as well

            foreach ($tagsToSync as $tag_id) {
                $parent = Tag::find($tag_id)->parent_tag;
                if ($parent != null && !in_array($parent->id, $tagsToSync)) {
                    $tagsToSync[] = $parent->id;
                }
            }
        }
        if (isset($input["tags_official"]["sync"]))
            $tagsToSync = array_merge($tagsToSync, $input["tags_official"]["sync"]);

        $song_lyric->tags()->sync($tagsToSync);

        // CREATE NEW TAGS
        if (isset($input["tags_unofficial"]["create"])) {
            foreach ($input["tags_unofficial"]["create"] as $author) {
                $song_lyric->tags()->create(['name' => $author["name"]]);
            }
        }
        // $song_lyric->save();

        // HANDLE ASSOCIATED SONG LYRICS
        if (isset($input["song"])) {
            $sl_group = collect($input["song"]["song_lyrics"]);

            // 1. case: song was alone and now is added to a group
            if (!$song_lyric->hasSiblings() && $sl_group->count() > 1) {
                // add this song to that foreign group - aka change the song_id
                // todo: check that there are only two different song ids
                Log::info("situation 1");

                foreach ($sl_group as $sl_object) {
                    $new_sibling = SongLyric::find($sl_object["id"]);
                    $new_sibling->update([
                        'song_id' => $input["song"]["id"],
                        'type' => $sl_object["type"]
                    ]);
                }
            }
            // 2. case: song was in a group and now is alone
            elseif ($song_lyric->hasSiblings() && $sl_group->count() == 1) {
                // create new song and associate this song_lyric to that one
                Log::info("situation 2");

                $sl_object = $sl_group[0];

                $new_song = Song::create(["name" => $sl_object["name"]]);
                $former_sibling = SongLyric::find($sl_object["id"]);
                $former_sibling->update([
                    'song_id' => $new_song->id,
                    'type' => $sl_object["type"]
                ]);
            }
            // 3. case: no insertions/deletions, just update the types
            elseif ($song_lyric->getSiblings()->count() + 1 == $sl_group->count()) {
                foreach ($sl_group as $sl_object) {
                    $sibling = SongLyric::find($sl_object["id"]);
                    $sibling->update([
                        'type' => $sl_object["type"]
                    ]);
                }
            } else {
                // todo: validation error
                Log::error("situation 3 - error");
            }
        }

        // HANDLE SONGBOOK RECORDS
        if (isset($input["songbook_records"]["sync"])) {
            $syncModels = [];
            foreach ($input["songbook_records"]["sync"] as $record) {
                $syncModels[$record["songbook_id"]] = [
                    'number' => $record["number"]
                ];
            }
            $song_lyric->songbook_records()->sync($syncModels);
        }
        // if (isset($input["songbook_records"]["create"])) {
        //     foreach ($input["songbook_records"]["create"] as $record) {
        //         // $songbook = Songbook::create(["name" => $record["songbook"]]);

        //         \Log::info($song_lyric);

        //         $song_lyric->songbook_records()->create([
        //             'name' => $record["songbook"]
        //         ], [
        //             'number' => $record["number"]
        //         ]);
        //     }
        // }

        $song_lyric->save();

        // reload from database
        return SongLyric::find($input["id"]);
    }
}
