<?php

namespace App\GraphQL\Mutations;

use App\Notifications\SongLyricUpdated;
use App\Services\SongLyricLilypondService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\SongLyric;
use App\Services\SongLyricModelService;

class UpdateSongLyric
{
    protected $sl_service;
    protected $sl_lily_service;

    public function __construct(SongLyricModelService $sl_service, SongLyricLilypondService $sl_lily_service)
    {
        $this->sl_service = $sl_service;
        $this->sl_lily_service = $sl_lily_service;
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
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $input = $args["input"];

        $song_lyric = SongLyric::find($input["id"]);
        // $song_lyric_old = $song_lyric->replicate();

        // if has key
        $this->sl_lily_service->handleLilypondOnUpdate($song_lyric, $input["lilypond"], $input["lilypond_key_major"], $input["lilypond_parts_sheet_music"]);

        $song_lyric->update($input);
        // todo if has key
        if (isset($input["lyrics"])) {
            $this->sl_service->handleLyrics($song_lyric, $input["lyrics"]);
        }
        if (isset($input["arrangement_source"])) {
            $this->sl_service->handleArrangementSourceUpdate($song_lyric, $input["arrangement_source"]);
        }
        if (isset($input["song"])) {
            $this->sl_service->handleSongGroup($song_lyric, $input["song"]);
        }
        $this->sl_service->handleHasChords($song_lyric);
        if (isset($input["authors"])) {
            $this->sl_service->handleAuthors($song_lyric, $input["authors"]);
        }
        if (isset($input["songbook_records"])) {
            $this->sl_service->handleSongbookRecords($song_lyric, $input["songbook_records"]);
        }
        $this->sl_service->handleRevisionAssociacionsStats($song_lyric);
        if (isset($input["bible_refs_osis"])) {
            $this->sl_service->handleBibleReferences($song_lyric, $input['bible_refs_osis']);
        }

        $song_lyric->save();

        // Send update notification
        $song_lyric->notify(new SongLyricUpdated());

        // reload from database
        return SongLyric::find($input["id"]);
    }
}
