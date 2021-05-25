<?php

namespace App\GraphQL\Mutations;

use App\SongLyric;
use App\Visit;

class VisitSong
{
    public function resolve($rootValue, array $args)
    {
        Visit::create([
            'visitable_type' => SongLyric::class,
            'visitable_id' => $args['song_lyric_id'],
            'is_mobile' => $args['is_mobile'] ?? null,
            'visit_type' => $args['visit_type'],
            'source' => $args['source']
        ]);

        return [
            "confirmed" => true,
        ];
    }
}
