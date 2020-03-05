<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\External;
use Exception;

class Externals
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $query = External::query();

        if (array_has($args, 'type'))
          $query = $query->where('type', $args['type']);

        if (array_has($args, 'is_todo') && $args['is_todo'])
          $query = $query->todo();

        if (array_has($args, 'orderBy')) {
          $orderConfig = $args['orderBy'][0];

          // the `field` and `order` elements are validated by lighthouse, no need to check again here
          $query = $query->orderBy($orderConfig['field'], $orderConfig['order']);
        }

        return $query->get();
    }
}
