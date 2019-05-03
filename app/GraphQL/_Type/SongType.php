<?php

namespace App\GraphQL\Type;

use GraphQL;
use App\Song;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SongType extends GraphQLType
{
	protected $attributes = [
		'name' => 'Song',
		'description' => 'An abstract Song model',
		'model' => Song::class
	];

	/*
	 * Uncomment following line to make the type input object.
	 * http://graphql.org/learn/schema/#input-types
	 */
	// protected $inputObject = true;

	public function fields()
	{
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The id of the song'
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'The name of the song'
			],
			'song_lyrics' => [
				'args' => [
					'id' => [
						'type' => Type::int(),
						'description' => 'Id of the author'
					],
					'is_original' => [
						'type' => Type::boolean(),
						'description' => 'Filtering by the is_original attribute'
					]
				],
				'type' => Type::listOf(GraphQL::type('song_lyric')),
				'description' => 'Child SongLyric models'
			]
		];
	}

	public function resolveSongLyricsField($root, $args)
	{
		$query = $root->song_lyrics();

		if (isset($args['id']))
			$query = $query->where('id', $args['id']);

		if (isset($args['is_original']))
			$query = $query->where('is_original', $args['is_original']);

		return $query->get();
	}
}

