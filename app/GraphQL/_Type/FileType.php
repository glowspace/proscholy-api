<?php

namespace App\GraphQL\Type;

use GraphQL;
use App\File;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FileType extends GraphQLType {

	protected $attributes = [
		'name' => 'File',
		'description' => 'A file model',
		'model' => File::class
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
				'description' => 'The id of the file'
			],
			'public_name' => [
				'type' => Type::string(),
				'description' => 'The public name of the file'
			],
			'download_url' => [
				'type' => Type::string(),
				'description' => 'The download url of the file'
			],
			'type' => [
                'type' => Type::int(),
                'description' => "multiple files"
			],
			'type_string' => [
                'type' => Type::string(),
                'description' => "multiple files"
			],
		];
	}
}