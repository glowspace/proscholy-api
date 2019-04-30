<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

use App\External;

class DeleteExternalMutation extends Mutation {
    protected $attributes = [
        'name' => 'delete_external'
    ];

    public function type()
    {
        return GraphQL::type('external');
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

        $external = External::find($args['id']);
        if (!$external)
            return;

        $external->delete();
    }
}