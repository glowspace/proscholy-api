<?php

namespace App\GraphQL\Type;

use GraphQL;
use App\External;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ExternalType extends GraphQLType {

	protected $attributes = [
		'name' => 'External',
		'description' => 'An external model',
		'model' => External::class
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
				'description' => 'The id of the external'
			],
			'public_name' => [
				'type' => Type::string(),
				'description' => 'The parsed public name of the external'
			],
			'type' => [
                'type' => Type::int(),
                'description' => "multiple types"
			],
			'type_string' => [
                'type' => Type::string(),
                'description' => "multiple types"
			],
		];
	}
}