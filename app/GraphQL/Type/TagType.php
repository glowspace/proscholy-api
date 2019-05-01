<?php

namespace App\GraphQL\Type;

use GraphQL;
use App\Tag;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TagType extends GraphQLType {

	protected $attributes = [
		'name' => 'Tag',
        'description' => 'A tag model',
        'model' => Tag::class
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
				'description' => 'The id of the tag'
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'The name of the tag'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => "Tag's description"
			],
			'type' => [
                'type' => Type::int(),
                'description' => "0 -> unofficial, 1 -> official aka liturgy."
			],
			'parent_tag' => [
				'type' => GraphQL::type('tag'),
				'description' => "Null or parent tag model"
			],
			'child_tags' => [
				'type' => Type::listOf(GraphQL::type('tag')),
				'description' => "List of child tags"
			]
		];
	}
}