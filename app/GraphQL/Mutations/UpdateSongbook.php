<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Illuminate\Support\Facades\Validator;
use App\Songbook;
use App\SongLyric;
use App\Song;

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
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
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
                ];
            }
            $songbook->records()->sync($syncModels);
        }

        if (isset($input["records"]["create"])) {
            foreach ($input["records"]["create"] as $record) {
                $song_name = $record['song_lyric_name'];
                // todo: review this functionality (also on frontend.. (no error message is shown there))
                $validator = Validator::make(['name' => $song_name], ['name' => 'unique:song_lyrics'], ['unique' => "Jméno písně $song_name už je obsazené"]);
                $validator->validate();

                $song       = Song::create(['name' => $song_name]);
                $song_lyric = SongLyric::create([
                    'name' => $song_name,
                    'song_id' => $song->id,
                ]);

                $songbook->records()->attach([$song_lyric->id => [
                    'number' => $record["number"],
                ]]);
            }
        }
        $songbook->save();

        return Songbook::find($input["id"]);
    }
}
