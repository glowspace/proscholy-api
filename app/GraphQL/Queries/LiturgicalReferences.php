<?php

namespace App\GraphQL\Queries;

use App\LiturgicalReference;

class LiturgicalReferences
{
    public function __invoke($rootValue, array $args)
    {
        $q = LiturgicalReference::with('song_lyric');

        if (isset($args['date'])) {
            $q = $q->where('date', $args['date']);
        }

        $refs = $q->get()->groupBy(['date', 'song_lyric_id']);

        $res = $refs->flatMap(function ($group_date) {
            return $group_date->map(function ($group_song) {
                return $group_song->reduce(function ($carry, $item) {
                    return [
                        'song_lyric' => $item->song_lyric, // is same within the group
                        'date' => $item->date, // is same within the group

                        'readings' => $carry['readings']->push([
                            'id' => $item->id,
                            'type' => $item->type,
                            'cycle' => $item->cycle,
                            'reading_reference' => $item->reading
                        ])
                    ];
                }, ['readings' => collect()]);
            });
        });

        return $res;
    }
}
