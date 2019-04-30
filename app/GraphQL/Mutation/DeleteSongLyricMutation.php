<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

use App\SongLyric;

class DeleteSongLyricMutation extends Mutation {
    protected $attributes = [
        'name' => 'delete_song_lyric'
    ];

    public function type()
    {
        return GraphQL::type('song_lyric');
    }

    public function args()
    {
        return [
            'id' => [
				'name' => 'id',
				'type' => Type::int()
			]
        ];
    }

    public function resolve($root, $args)
    {
        // todo check auth

        $song_lyric = SongLyric::find($args['id']);
        if (!$song_lyric)
            return;

        $song_lyric->delete();
    }
}