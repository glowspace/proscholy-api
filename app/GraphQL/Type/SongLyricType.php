<?php

namespace App\GraphQL\Type;

// use GraphQL;

use App\SongLyric;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SongLyricType extends GraphQLType {

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
		];
	}
}