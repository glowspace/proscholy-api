<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

use App\File;

class FilesQuery extends Query {

	protected $attributes = [
		'name' => 'files',
		'description' => 'A Query for File model'
	];

	public function type()
	{
		return Type::listOf(GraphQL::type('file'));
	}

	public function args()
	{
		return [
			'id' => ['name' => 'id', 'type' => Type::int()],
			'type' => ['name' => 'type', 'type' => Type::int()],
			'is_todo' => [
				'name' => 'is_todo',
				'type' => Type::boolean(),
				'description' => 'Whether File doesn\'t have any author or song associated'
			],
		];
	}

	public function resolve($root, $args)
	{
		$query = File::query();

		if (isset($args['id']))
			$query = $query->where('id' , $args['id']);

		if (isset($args['type']))
			$query = $query->where('type', $args['type']);

		if (isset($args['is_todo']) && $args['is_todo'])
			$query = $query->where('author', null)->orWhere('song', null);

		return $query->get();
	}
}