<?php

namespace App\Services;

use App\SongLyric;
use App\SongLyricBibleReference;

class SongLyricBibleReferenceService
{
    public function createBibleReferenceFromOsis($osis)
    {
        $start_end = explode('-', $osis);
        $start = explode('.', $start_end[0]);
        $end = count($start_end) > 1 ? explode('.', $start_end[1]) : $start;

        return new SongLyricBibleReference([
            'book' => $start[0],
            'start_chapter' =>  $start[1],
            'start_verse' => $start[2],
            'end_chapter' => $end[1],
            'end_verse' => $end[2]
        ]);
    }

    public function findMatchingSongLyricIds($osis)
    {
        $references = explode(',', $osis);
        $query = SongLyricBibleReference::query();

        foreach ($references as $reference) {
            $start_end = explode('-', $reference);
            $start = explode('.', $start_end[0]);
            $end = count($start_end) > 1 ? explode('.', $start_end[1]) : $start;

            $query->orWhere(function ($subq) use ($start, $end) {
                $subq->where('book', $start[0])
                    ->isIncludedIn($start[1], $start[2], $end[1], $end[2]);
            });
        }

        $sl_ids = $query->select('song_lyric_id')->get()->pluck('song_lyric_id');
        return $sl_ids;
    }
}
