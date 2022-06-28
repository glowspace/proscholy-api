<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\External;
use Illuminate\Support\Arr;
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
  public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $query = External::query();

    if (Arr::has($args, 'media_type') && $args['media_type'])
      $query = $query->where('media_type', $args['media_type']);

    if (Arr::has($args, 'content_type') && $args['content_type'])
      $query = $query->where('content_type', $args['content_type']);

    if (Arr::has($args, 'is_uploaded') && $args['is_uploaded'])
      $query = $query->where('is_uploaded', $args['is_uploaded']);

    if (Arr::has($args, 'is_todo') && $args['is_todo'])
      $query = $query->todo();

    if (Arr::has($args, 'orderBy')) {
      $orderConfig = $args['orderBy'][0];

      // the `field` and `order` elements are validated by lighthouse, no need to check again here
      $query = $query->orderBy($orderConfig['field'], $orderConfig['order']);
    }

    return $query->get();
  }
}
