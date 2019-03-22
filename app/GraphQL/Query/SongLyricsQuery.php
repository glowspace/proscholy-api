<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

use App\SongLyric;

class SongLyricsQuery extends Query {

	protected $attributes = [
		'name' => 'song_lyrics'
	];

	public function type()
	{
		return Type::listOf(GraphQL::type('song_lyric'));
	}

	public function args()
	{
		return [
            'id' => ['name' => 'id', 'type' => Type::int()],
		];
	}

	public function resolve($root, $args)
	{
        $public_songs = SongLyric::where('is_published', 1)->where('is_approved_by_author', 1);

		if(isset($args['id']))
			return $public_songs->where('id' , $args['id'])->get();
		else
			return $public_songs->get();
	}
}