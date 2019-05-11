<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Log;
use App\SongLyric;
use App\Author;

class UpdateSongLyric
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
        Log::info($args["input"]);
        $input = $args["input"];
        
        $song_lyric = SongLyric::find($input["id"]);
        
        // TODO as an event
        if ($input["name"] !== $song_lyric->name) {
            // to be domestic means to have a same name as the parent song
            // this invariant needs to be preserved in order to stay domestic
            if ($song_lyric->isDomestic()) {
                $song_lyric->song->update([
                    'name' => $input["name"]
                ]);
            }
        }

        $song_lyric->update($input);

        // HANDLE AUTHORS
        if (isset($input["authors"]["sync"]))
            $song_lyric->authors()->sync($input["authors"]["sync"]);
        if (isset($input["authors"]["create"])) {
            foreach ($input["authors"]["create"] as $author) {
                $song_lyric->authors()->create(['name' => $author["name"]]);
            }
        }

        $song_lyric->save();

        return $song_lyric;
    }
}
