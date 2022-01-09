<?php

namespace App\Services;

use App\SongLyricBibleReference;

class SongLyricBibleReferenceService
{
    public function createBibleReferenceFromOsis($osis)
    {
        $start_end = $osis->explode('-');
        $start = $start_end[0]->explode('.');
        $end = count($start_end) > 1 ? $start_end[1]->explode('.') : [null, null, null];

        return new SongLyricBibleReference([
            'book' => $start[0],
            'start_chapter' =>  $start[1],
            'start_verse' => $start[2],
            'end_chapter' => $end[1],
            'end_verse' => $end[2]
        ]);
    }

    public function findMatches($osis)
    {
        $start_end = $osis->explode('-');
        // e1
        $start = $start_end[0]->explode('.');
        $end = count($start_end) > 1 ? $start_end[1]->explode('.') : [null, null, null];

        // table row is e2
        return SongLyricBibleReference::where('book', $start[0])
            ->where(function ($q) use ($start, $end) {
                // e2.start is between e1.start and end
                $q->where('start_chapter', '>=', $start[1])
                    ->where('start_verse', '>=', $start[2])
                    ->where('start_chapter', '<=', $end[1])
                    ->where('start_verse', '<=', $end[2]);
            })->orWhere(function ($q) use ($start, $end) {
                // e2.end is between e1.start and end
                $q->where('end_chapter', '>=', $start[1])
                ->where('end_verse', '>=', $start[2])
                ->where('end_chapter', '<=', $end[1])
                ->where('end_verse', '<=', $end[2]);
            })->orWhere(function ($q) use ($start) {
                // e1.start is between e2.start and end
                $q->where('end_chapter', '>=', $start[1])
                ->where('end_verse', '>=', $start[2])
                ->where('start_chapter', '<=', $start[1])
                ->where('start_verse', '<=', $start[2]);
            });
    }
}
