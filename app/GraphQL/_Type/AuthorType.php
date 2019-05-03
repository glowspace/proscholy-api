<?php

namespace App\GraphQL\Type;

// use GraphQL;

use App\Author;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AuthorType extends GraphQLType {

	protected $attributes = [
		'name' => 'Author',
        'description' => 'An author model',
        'model' => Author::class
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
            'description' => [
                'type' => Type::string(),
                'description' => "Author's description"
			],
			'type' => [
                'type' => Type::int(),
                'description' => "Author's type"
            ],
			'type_string' => [
                'type' => Type::string(),
                'description' => "Author's type"
            ]
		];
	}
}