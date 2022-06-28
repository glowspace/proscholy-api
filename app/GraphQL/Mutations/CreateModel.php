<?php

namespace App\GraphQL\Mutations;

use App\Notifications\SongLyricCreated;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
// use Validator;
use Nuwave\Lighthouse\Execution\ErrorPool;

use App\Author;
use App\External;
use App\NewsItem;
use App\Services\SongLyricModelService;
use App\Songbook;
use App\Tag;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $input = $args["input"];
        $attr = $input["required_attribute"];

        // perform the validation with the help of Nuawe validation error pool
        /** @var ErrorPool */
        $errorPool = app(ErrorPool::class);

        // until we check the data with Validator, store the return data here
        $returnValue = null;
        /** @var Validator|null */
        $validator = null;
        
        if ($input["class_name"] == "Author") {
            $validator = Validator::make(['name' => $attr], ['name' => 'unique:authors'], ['unique' => 'Autor se stejným jménem již existuje']);
            if (!$validator->fails()) {
                $author = Author::create(['name' => $attr]);

                $returnValue = [
                    "id" => $author->id,
                    "class_name" => "Author",
                    "edit_url" => route("admin.author.edit", $author)
                ];
            }
        } elseif ($input["class_name"] == "External") {
            $validator = Validator::make(['url' => $attr], ['url' => 'unique:externals'], ['unique' => 'Materiál s daným URL již existuje']);
            if (!$validator->fails()) {
                $external = External::create(['url' => $attr]);

                $returnValue = [
                    "id" => $external->id,
                    "class_name" => "External",
                    "edit_url" => route("admin.external.edit", $external)
                ];
            }
        } elseif ($input["class_name"] == "Songbook") {
            $validator = Validator::make(['name' => $attr], ['name' => 'unique:songbooks'], ['unique' => 'Zpěvník se stejným jménem již existuje']);
            if (!$validator->fails()) {
                $songbook = Songbook::create(['name' => $attr]);

                $returnValue = [
                    "id" => $songbook->id,
                    "class_name" => "Songbook",
                    "edit_url" => route("admin.songbook.edit", $songbook)
                ];
            }
        } elseif ($input["class_name"] == "SongLyric") {
            $song_lyric = app(SongLyricModelService::class)->createSongLyric($attr);

            $returnValue = [
                "id" => $song_lyric->id,
                "class_name" => "SongLyric",
                "edit_url" => route("admin.song.edit", $song_lyric)
            ];

            // Send create notifications
            $song_lyric->notify(new SongLyricCreated());
        } elseif ($input["class_name"] == "NewsItem") {
            $news_item = NewsItem::create(['link' => $attr]);

            $returnValue = [
                "id" => $news_item->id,
                "class_name" => "NewsItem",
                "edit_url" => route("admin.news-item.edit", $news_item)
            ];
        } elseif ($input["class_name"] == "Tag") {
            if (Tag::where('type', $input['tag_type'])->where('name', $attr)->count() > 0) {
                // $validationErrorBuffer->push("Jméno štítku už je obsazené v rámci kategorie", "required_attribute");
                // $validationErrorBuffer->flush(
                //     "Validation failed for the field [input.required_attribute]."
                // );

                $errorPool->record(new Exception('Jméno štítku už je obsazené v rámci kategorie'));
            } else {
                $tag = Tag::create(['name' => $attr, 'type' => $input["tag_type"]]);
    
                $returnValue = [
                    "id" => $tag->id,
                    "class_name" => "Tag",
                    "edit_url" => route("admin.tag.edit", $tag)
                ];
            }

        } else {
            // todo throw an error?
            return;
        }

        if (isset($validator)) {
            // // validator has already been tested for fail, so here ->failed() is needed
            // // in order not to perform the validation again
            // if ($validator->failed()) {
            //     foreach ($validator->errors()->getMessages() as $key => $errorMessages) {
            //         foreach ($errorMessages as $errorMessage) {
            //             // we use only one attribute - required_attribute, for the sake of simplicity
            //             // every failure goes to required_attribute which is used then in generic view CreateModel.vue
            //             $validationErrorBuffer->push($errorMessage, "required_attribute");

            //             $errorPool->record()
            //         }
            //     }
            // }

            // $path = implode('.', $resolveInfo->path);
            // $validationErrorBuffer->flush(
            //     "Validation failed for the field [$path]."
            // );

            try {
                $validator->validate();
            } catch (ValidationException $e) {
                $errorPool->record($e);
            }
        }

        return $returnValue;
    }
}
