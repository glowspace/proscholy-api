<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\Author;
use App\SongLyric;
use App\Song;
use App\External;

class CreateModel
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

        if ($input["class_name"] == "Author") {
            $author = Author::create(['name' => $input["required_attribute"]]);

            return [
                "id" => $author->id,
                "class_name" => "Author",
                "edit_url" => route("admin.author.edit", $author)
            ];

        } elseif($input["class_name"] == "External") {
            $external = External::create(['url' => $input["required_attribute"]]);

            return [
                "id" => $external->id,
                "class_name" => "External",
                "edit_url" => route("admin.external.edit", $external)
            ];

        } elseif ($input["class_name"] == "SongLyric") {
            $song       = Song::create(['name' => $input["required_attribute"]]);
            $song_lyric = SongLyric::create([
                'name' => $input["required_attribute"],
                'song_id' => $song->id
            ]);

            return [
                "id" => $song_lyric->id,
                "class_name" => "SongLyric",
                "edit_url" => route("admin.song.edit", $song_lyric)
            ];

        } else {
            // todo throw an error
            return;
        }
    }
}
