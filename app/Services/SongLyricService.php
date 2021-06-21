<?php

namespace App\Services;

use Log;
use App\Author;
use App\SongLyric;
use App\Song;

use App\Jobs\UpdateSongLyricLilypond;
use App\SongLyricLilypondSrc;
use App\LilypondPartsSheetMusic;
use App\SongLyricLilypondSvg;
use App\SongLyricLyrics;

class SongLyricService
{
    protected SongLyricLilypondService $ly_service;

    public function __construct(SongLyricLilypondService $ly_service)
    {
        $this->ly_service = $ly_service;
    }

    public function createSongLyric(string $name): SongLyric
    {
        $song       = Song::create(['name' => $name]);

        $song_lyric = SongLyric::create([
            'name' => $name,
            'song_id' => $song->id
        ]);

        $song_lyric->visit_aggregate()->create([
            'count_week' => 0,
            'count_total' => 0
        ]);

        return $song_lyric;
    }

    public function createArrangement(string $name, int $arrangement_of_id): SongLyric
    {
        return SongLyric::create([
            'name' => $name,
            'arrangement_of' => $arrangement_of_id,
            'type' => null,
            'song_id' => null // arrangement has no `song` - meaning belongs to no song group per definition
        ]);
    }

    // todo: rewrite following (three) methods to use `upsert`

    public function handleLyrics($song_lyric, $lyrics)
    {
        $wasEmpty = $song_lyric->lyrics === null;
        $isEmpty = $lyrics === null || $lyrics === '';

        if ($wasEmpty && !$isEmpty) {
            $lyrics_obj = new SongLyricLyrics(['lyrics' => $lyrics]);
            $song_lyric->lyrics()->save($lyrics_obj);
        } else if (!$wasEmpty && !$isEmpty) {
            $song_lyric->lyrics()->update(['lyrics' => $lyrics]);
        } else if (!$wasEmpty && $isEmpty) {
            $song_lyric->lyrics()->delete();
        }

        $song_lyric->touch();
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

    public function handleHasChords(SongLyric $song_lyric)
    {
        $song_lyric->update(['has_chords' => $this->songLyricHasChords($song_lyric)]);
    }

    public function handleAuthors(SongLyric $song_lyric, $authors_data)
    {
        // HANDLE AUTHORS
        $syncAuthors = [];

        if (isset($authors_data["create"])) {
            foreach ($authors_data["create"] as $author) {
                $a = Author::create([
                    'name' => $author['author_name']
                ]);

                $syncAuthors[$a->id] = [
                    'authorship_type' => $author['authorship_type']
                ];
            }
        }

        if (isset($authors_data["sync"])) {
            foreach ($authors_data['sync'] as $author) {
                $syncAuthors[$author["author_id"]] = [
                    'authorship_type' => $author["authorship_type"]
                ];
            }
        }

        $song_lyric->authors_pivot()->sync($syncAuthors);
        $song_lyric->save();
    }

    public function handleSongbookRecords(SongLyric $song_lyric, $songbooks_data)
    {
        // HANDLE SONGBOOK RECORDS
        if (isset($songbooks_data["sync"])) {
            $syncModels = [];
            foreach ($songbooks_data["sync"] as $record) {
                $syncModels[$record["songbook_id"]] = [
                    'number' => $record["number"]
                ];
            }
            $song_lyric->songbook_records()->sync($syncModels);
        }

        // OLD CODE (kept for legacy)
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
    }

    public function handleRevisionAssociacionsStats(SongLyric $song_lyric)
    {
        // handle tags count
        $song_lyric->update([
            'revision_n_tags' => $song_lyric->tags->count(),
            'revision_n_authors' => $song_lyric->authors->count(),
            'revision_n_songbook_records' => $song_lyric->songbook_records()->count(),
        ]);

        // externals are handled differently
    }

    public function songLyricHasChords($song_lyric)
    {
        if (strpos($song_lyric->lyrics, '[') === false) {
            return false;
        }

        return true;
    }
}
