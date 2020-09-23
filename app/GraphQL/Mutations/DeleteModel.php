<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\SongLyric;
use App\Author;
use App\External;
use App\File;
use App\Tag;
use App\Songbook;
use App\NewsItem;

use Illuminate\Support\Facades\Storage;

class DeleteModel
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
        $id = $input["id"];
        $succ = false;

        if ($input["class_name"] == "Author") {
            $succ = Author::destroy($id);
        } elseif ($input["class_name"] == "External") {
            $succ = External::destroy($id);
        } elseif ($input["class_name"] == "SongLyric") {
            $succ = SongLyric::destroy($id);
        } elseif ($input["class_name"] == "File") {
            $succ = File::destroy($id);
        } elseif ($input["class_name"] == "Tag") {
            $succ = Tag::destroy($id);
        } elseif ($input["class_name"] == "Songbook") {
            $succ = Songbook::destroy($id);
        } elseif ($input["class_name"] == "NewsItem") {
            $succ = NewsItem::destroy($id);
        } else {
            // todo throw an error
            return;
        }

        if (!$succ) {
            return;
        }

        return [
            "id" => $id,
        ];
    }
}
