<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Auth;
// use Validator;
use Validator;
use Illuminate\Validation\ValidationException;

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
        $attr = $input["required_attribute"];

        
        // $validator = Validator::make([], []);

        if ($input["class_name"] == "Author") {

            // dynamically create the request object - needed for proper validation
            // $request = new Request([
            //     'name' => $input["required_attribute"]
            // ]);

            // todo: somehow add customAttributes attribute to make it GraphQL validated

            $validator = Validator::make(['name' => $attr], ['name' => 'unique:authors']);
            if ($validator->fails()) {
                \Log::info(new ValidationException($validator));
                throw new ValidationException($validator);
            }

            $author = Author::create(['name' => $attr]);

            return [
                "id" => $author->id,
                "class_name" => "Author",
                "edit_url" => route("admin.author.edit", $author)
            ];

        } elseif($input["class_name"] == "External") {
            $external = External::create(['url' => $attr]);

            return [
                "id" => $external->id,
                "class_name" => "External",
                "edit_url" => route("admin.external.edit", $external)
            ];

        } elseif ($input["class_name"] == "SongLyric") {
            $song       = Song::create(['name' => $attr]);
            $song_lyric = SongLyric::create([
                'name' => $attr,
                'song_id' => $song->id,
                // 'is_published' => Auth::user()->can('publish songs'),
                // 'user_creator_id' => Auth::user()->id
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
