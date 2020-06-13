<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;
use App\SongLyric;
use App\Services\SongLyricService;
use App\Song;
use App\Tag;
use App\Songbook;
use function Safe\array_combine;

class UpdateSongLyric
{
    protected $sl_service;

    public function __construct(SongLyricService $slservice)
    {
        $this->sl_service = $slservice;
    }

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

        if ($input["lilypond"] !== $song_lyric->lilypond) {
            $input['lilypond_svg'] = $this->sl_service->getLilypondSvg($input['lilypond']);
            logger($input['lilypond']);
            logger($input['lilypond_svg']);
        }

        $song_lyric->update($input);

        // todo if has key
        $this->sl_service->handleArrangementSourceUpdate($song_lyric, $input["arrangement_source"]);
        $this->sl_service->handleSongGroup($song_lyric, $input["song"]);

        // HANDLE AUTHORS
        if (isset($input["authors"]["sync"]))
            $song_lyric->authors()->sync($input["authors"]["sync"]);
        if (isset($input["authors"]["create"])) {
            foreach ($input["authors"]["create"] as $author) {
                $song_lyric->authors()->create(['name' => $author["name"]]);
            }
        }
        $song_lyric->save();

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
