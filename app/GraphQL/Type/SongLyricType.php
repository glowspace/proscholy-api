<?php

namespace App\GraphQL\Type;

use GraphQL;
use App\SongLyric;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SongLyricType extends GraphQLType
{

	protected $attributes = [
		'name' => 'SongLyric',
		'description' => 'A song lyric model',
		'model' => SongLyric::class
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
			'lyrics_no_chords' => [
				'type' => Type::string(),
				'description' => 'The lyrics without chords'
			],
			'is_original' => [
				'type' => Type::boolean(),
				'description' => "0 means this version is translated from an original. 1 means this version is original."
			],
			'lang' => [
				'type' => Type::string(),
				'description' => "A language code, possible values: ".json_encode(collect(SongLyric::$lang_string)->keys())
			],
			'authors' => [
				'args' => [
					'id' => [
						'type' => Type::int(),
						'description' => 'Id of the author'
					]
				],
				'type' => Type::listOf(GraphQL::type('author')),
				'description' => 'Authors of the song'
			],
			'tags' => [
				'args' => [
					'type' => [
						'type' => Type::int(),
						'description' => 'Type of the tag'
					]
				],
				'type' => Type::listOf(GraphQL::type('tag')),
				'description' => 'Song tags'
			],
			'song' => [
			    'type' => GraphQL::type('song'),
			    'description' => "An abstract Song model that has one or more child SongLyric models"
			],
		];
	}

	public function resolveAuthorsField($root, $args)
	{
		$query = $root->authors();

		if (isset($args['id']))
			$query = $query->where('id', $args['id']);

		return $query->get();
	}

	public function resolveTagsField($root, $args)
	{
		$query = $root->tags();

		if (isset($args['type']))
			$query = $query->where('type', $args['type']);

		return $query->get();
	}
}

