<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

use App\External;

class ExternalsQuery extends Query {

	protected $attributes = [
		'name' => 'externals',
		'description' => 'A Query for External model'
	];

	public function type()
	{
		return Type::listOf(GraphQL::type('tag'));
	}

	public function args()
	{
		return [
			'id' => ['name' => 'id', 'type' => Type::int()],
			'type' => ['name' => 'type', 'type' => Type::int()]
		];
	}

	public function resolve($root, $args)
	{
		$query = External::query();

		if (isset($args['id']))
			$query = $query->where('id' , $args['id']);

		if (isset($args['type']))
			$query = $query->where('type', $args['type']);

		return $query->get();
	}
}