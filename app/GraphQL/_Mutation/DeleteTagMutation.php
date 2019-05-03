<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

use App\Tag;

class DeleteTagMutation extends Mutation {
    protected $attributes = [
        'name' => 'delete_tag'
    ];

    public function type()
    {
        return GraphQL::type('tag');
    }

    public function args()
    {
        return [
            'id' => [
				'name' => 'id',
				'type' => Type::int()
			]
        ];
    }

    public function resolve($root, $args)
    {
        // todo check auth
        // todo error when not found

        $tag = Tag::find($args['id']);
        if (!$tag)
            return;

        $tag->delete();
    }
}