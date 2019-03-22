<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

use App\Tag;

class TagsQuery extends Query {

	protected $attributes = [
		'name' => 'tags'
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
		if(isset($args['id']))
			return Tag::where('id' , $args['id'])->get();
		else if (isset($args['type']))
			return Tag::where('type', $args['type'])->get();
		else
			return Tag::all();
	}
}