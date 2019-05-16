<?php

namespace App\GraphQL\Queries;

use App\Author;

class Authors
{
    public function resolve($rootValue, array $args)
    {
        $query = Author::query();

		if (isset($args['search_string']))
            return Author::search($args['search_string'])->get();
            
        if (isset($args['order_abc']))
            $query = $query->orderBy('name', 'asc');

        if (isset($args['type']))
            $query = $query->where('type', $args['type']);
		
		return $query->get();
    }
}
