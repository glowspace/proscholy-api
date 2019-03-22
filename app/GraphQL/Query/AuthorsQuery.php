<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

use App\Author;

class AuthorsQuery extends Query {

	protected $attributes = [
		'name' => 'authors'
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
		if(isset($args['id']))
			return Author::where('id' , $args['id'])->get();
		else
			return Author::all();
	}
}