<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Execution\ErrorBuffer;

use Validator;
use App\SongLyric;
use App\PublicModels\Playlist;

class UpdatePlaylist
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
        $playlist = Playlist::find($input["id"]);

        // $validationErrorBuffer = (new ErrorBuffer)->setErrorType('validation');
        // $validatorCustomAttributes = ['resolveInfo' => $resolveInfo, 'context' => $context,'root' => $rootValue];

        // update the fillable attributes
        $playlist->update($input);

        if (isset($input["song_lyrics"])) {
            $playlist->song_lyrics()->sync(
                $input["song_lyrics"]->map(function ($id, $key) {
                    return [
                        'song_lyric_id' => $id,
                        'order' => $key
                    ];
                })
            );
        }
        
        // if (isset($input["records"]["create"])) {
        //     foreach ($input["records"]["create"] as $record) {
        //         $validator = Validator::make(['name' => $record["song_lyric_name"]], ['name' => 'unique:song_lyrics'], ['unique' => 'Jméno písně už je obsazené'], $validatorCustomAttributes);
                
        //         // \Log::info($record);

        //         if (!$validator->fails()){
        //             $song       = Song::create(['name' => $record["song_lyric_name"]]);
        //             $song_lyric = SongLyric::create([
        //                 'name' => $record["song_lyric_name"],
        //                 'song_id' => $song->id,
        //             ]);

        //             $songbook->records()->attach([$song_lyric->id => [
        //                 'number' => $record["number"],
        //             ]]);
        //         }
        //     }
        // }
        $playlist->save();

        // // perform the validation with the help of Nuawe validation error buffer
        // if (isset($validator)) {
        //     // validator has already been tested for fail, so here ->failed() is needed 
        //     // in order not to perform the validation again
        //     if ($validator->failed()) {
        //         foreach ($validator->errors()->getMessages() as $key => $errorMessages) {
        //             foreach ($errorMessages as $errorMessage) {
        //                 $validationErrorBuffer->push($errorMessage, $key);
        //             }
        //         }
        //     }
    
        //     $path = implode('.', $resolveInfo->path);
        //     $validationErrorBuffer->flush(
        //         "Validation failed for the field [$path]."
        //     );
        // }

        return Playlist::find($input["id"]);
    }
}
