<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\SongLyric;
use GuzzleHttp\Client;
use Exception;

class SongLyricsBibleReference
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
        // get song ids from external docker service

        $endpoint = config('bible-matcher.host') . ":" . config('bible-matcher.port') . '/get-songs';

        $client = new Client();
        $res = $client->get($endpoint, [
            'query' => [
                'reference_str' => $args['reference_str'],
                'is_osis' => $args['reference_type'] == 'OSIS'
            ]
        ]);

        if ($res->getStatusCode() == 200) {
            $ids = json_decode($res->getBody());

            return SongLyric::whereIn('id', $ids)->get();
        }

        throw new Exception("Couldn't match bible references", $res->getStatusCode());
    }
}
