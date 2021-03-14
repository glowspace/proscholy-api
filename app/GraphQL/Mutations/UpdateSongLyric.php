<?php

namespace App\GraphQL\Mutations;

use App\Notifications\SongLyricUpdated;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\SongLyric;
use App\Services\SongLyricService;

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
        // $song_lyric_old = $song_lyric->replicate();

        $this->sl_service->handleLilypond($song_lyric, $input["lilypond"], $input["lilypond_key_major"]);

        $song_lyric->update($input);
        // todo if has key
        $this->sl_service->handleLyrics($song_lyric, $input["lyrics"]);
        $this->sl_service->handleArrangementSourceUpdate($song_lyric, $input["arrangement_source"]);
        $this->sl_service->handleSongGroup($song_lyric, $input["song"]);
        $this->sl_service->handleHasChords($song_lyric);
        $this->sl_service->handleAuthors($song_lyric, $input["authors"]);
        $this->sl_service->handleSongbookRecords($song_lyric, $input["songbook_records"]);
        $this->sl_service->handleRevisionAssociacionsStats($song_lyric);

        $song_lyric->save();

        // Send update notification to Slack
        $song_lyric->notify(new SongLyricUpdated());

        // reload from database
        return SongLyric::find($input["id"]);
    }
}
