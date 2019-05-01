<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

use App\Author;

class DeleteAuthorMutation extends Mutation {
    protected $attributes = [
        'name' => 'delete_author'
    ];

    public function type()
    {
        return GraphQL::type('author');
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

        $author = Author::find($args['id']);
        if (!$author)
            return;

        $author->delete();
    }
}