<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\Songbook;

class UpdateSongbook
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
        $input = $args["input"];
        $songbook = Songbook::find($input["id"]);

        // update the fillable attributes
        $songbook->update($input);

        if (isset($input["records"]["sync"])) {
            $syncModels = [];
            foreach ($input["records"]["sync"] as $record) {
                $syncModels[$record["song_lyric_id"]] = [
                    'number' => $record["number"],
                    'placeholder' => $record["placeholder"],
                ];
            }
            $songbook->records()->sync($syncModels);
        }
        if (isset($input["records"]["create"])) {
            foreach ($input["records"]["create"] as $record) {
                $songbook->records()->create([
                    'number' => $record["number"],
                    'placeholder' => $record["placeholder"],
                ]);
            }
        }
        $songbook->save();

        return Songbook::find($input["id"]);
    }
}
