<?php

namespace App\GraphQL\Mutations;

use App\SongLyric;
use App\Visit;
use DB;

class VisitSongNumber
{
    public function resolve($rootValue, array $args)
    {
        $sl_number =  $args['song_lyric_number'];
        $sl = SongLyric::where('song_number', $sl_number)->first();

        if (!$sl) {
            throw new \Exception("Song with number #$sl_number not found");
        }

        DB::transaction(function () use ($args, $sl) {
            Visit::create([
                'visitable' => SongLyric::class,
                'visitable_id' => $sl->id,
                'is_mobile' => $args['is_mobile'] ?? null,
                'visit_type' => $args['visit_type'],
                'source' => $args['source']
            ]);

            // type 1 is LONG, meaning the user stayed long enough at the song page, don't count this to song counter
            if ($args['visit_type'] != 1) {
                $sl->increment('visits');
            }
        });

        return [
            "confirmed" => true,
        ];
    }
}
