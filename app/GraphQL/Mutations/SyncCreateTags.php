<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Tag;

class SyncCreateTags
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
        // todo: validate taggable is valid name
        // todo: validate type belongs to taggable group

        $input = $args["input"];
        $taggable = $args["taggable"];
        $taggable_id = $args["taggable_id"];
        $tags_type = $args["tags_type"];

        // get the model instance
        $model = $taggable::find($taggable_id);

        $tagsSync = $input["sync"];
        $parentTags = Tag::whereIn('id', $tagsSync)->whereNotNull('parent_tag_id')->get()->pluck('parent_tag_id')->toArray();
        // dd($parentTags);
        $tagsToSync = array_merge($tagsSync, $parentTags);
        
        // todo: check that all sync tags are really of given type

        // sync without detaching because there are tags of other types, that should be preserved / not detached
        $model->tags()->syncWithoutDetaching($tagsToSync);

        if (array_key_exists("create", $args)) {
            $tagsCreate = $input["create"];
            foreach ($tagsCreate as $newTag) {
                $model->tags()->create([
                    'name' => $newTag["name"],
                    'type' => $tags_type
                ]);
            }
        }

        return $model->tags()->where('type', $tags_type)->get();
    }
}
