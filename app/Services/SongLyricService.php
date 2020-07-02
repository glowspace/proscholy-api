<?php

namespace App\Services;

use Log;
use App\Author;
use App\SongLyric;
use GuzzleHttp\Client;
use Exception;

class SongLyricService
{
    public function getLilypondSvg($lilypond)
    {
        $endpoint = config('lilypond.host') . ":" . config('lilypond.port') . '/svg';

        $client = new Client();
        $res = $client->post($endpoint, [
            'multipart' => [
                [
                    'name'     => 'file_lilypond', // input name, needs to stay the same
                    'contents' => $lilypond,
                    'filename' => 'score.ly' // doesn't matter
                ]
            ]
        ]);

        if ($res->getStatusCode() == 200) {
            return $res->getBody();
        }

        throw new Exception("Error getting svg", $res->getStatusCode());
    }

    public function handleSongGroup(SongLyric $song_lyric, $song_input_data)
    {
        // HANDLE ASSOCIATED SONG LYRICS
        if (isset($song_input_data)) {
            $sl_group = collect($song_input_data["song_lyrics"]);

            // 1. case: song was alone and now is added to a group
            if (!$song_lyric->hasSiblings() && $sl_group->count() > 1) {
                // add this song to that foreign group - aka change the song_id
                // todo: check that there are only two different song ids
                Log::info("situation 1");

                foreach ($sl_group as $sl_object) {
                    $new_sibling = SongLyric::find($sl_object["id"]);
                    $new_sibling->update([
                        'song_id' => $song_input_data["id"],
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
    }

    public function handleArrangementSourceUpdate(SongLyric $song_lyric, $arrangement_source_data)
    {
        // do this only if the song is an arrangement
        if ($song_lyric->is_arrangement) {
            $arrangement_source_id = $arrangement_source_data["update"]["id"];
            $arrangement_source = SongLyric::find($arrangement_source_id);

            if ($arrangement_source->is_arrangement) {
                // todo throw error
                return;
            }

            $song_lyric->arrangement_source()->associate($arrangement_source);
        }
    }
}
