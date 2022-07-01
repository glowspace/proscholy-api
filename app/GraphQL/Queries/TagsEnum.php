<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\Tag;

class TagsEnum
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
        $query = Tag::query();

        if (isset($args['type'])) {
            $type = $args['type'];

            $query = $query->where('type', $type);

            // Svátosti a pobožnosti, K příležitostem
            if ($type == 0 || $type == 5) {
                $query = $query->orderBy('name');
            } else {
                // order by `order` column by default (when the type is defined)
                $query = $query->orderBy('order');
            }
        }

        if (isset($args['hide_in_liturgy']))
            $query = $query->where('hide_in_liturgy', $args['hide_in_liturgy']);

        return $query->get();
    }
}
