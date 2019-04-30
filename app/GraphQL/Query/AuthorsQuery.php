<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

use App\Author;

class AuthorsQuery extends Query {

	protected $attributes = [
		'name' => 'authors',
		'description' => 'A Query for the Author model'
	];

	public function type()
	{
		return Type::listOf(GraphQL::type('author'));
	}

	public function args()
	{
		return [
            'id' => ['name' => 'id', 'type' => Type::int()],
		];
	}

	public function resolve($root, $args)
	{
		$query = Author::query();

		if (isset($args['id']))
			$query = $query->where('id' , $args['id']);

		return $query->get();
	}
}