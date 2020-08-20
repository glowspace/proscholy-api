<?php

namespace App\GraphQL\Mutations;

use App\SongLyric;
use App\Visit;
use DB;

class VisitSong
{
    public function resolve($rootValue, array $args)
    {
        DB::transaction(function () use ($args) {
            Visit::create([
                'visitable' => SongLyric::class,
                'visitable_id' => $args['song_lyric_id'],
                'is_mobile' => $args['is_mobile'] ?? null,
                'visit_type' => $args['visit_type'],
                'source' => $args['source']
            ]);

            // type 1 is LONG, meaning the user stayed long enough at the song page, don't count this to song counter
            if ($args['visit_type'] != 1) {
                SongLyric::find($args['song_lyric_id'])->increment('visits');
            }
        });

        return [
            "confirmed" => true,
        ];
    }
}
