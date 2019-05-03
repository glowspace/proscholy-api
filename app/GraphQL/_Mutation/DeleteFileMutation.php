<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

use App\File;

class DeleteFileMutation extends Mutation {
    protected $attributes = [
        'name' => 'delete_file'
    ];

    public function type()
    {
        return GraphQL::type('file');
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

        $file = File::find($args['id']);
        if (!$file)
            return;

        $file->delete();
    }
}