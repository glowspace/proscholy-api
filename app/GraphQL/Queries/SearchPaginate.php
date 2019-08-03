<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\SongLyric;
use App\Author;

class Search
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
        $song_lyrics_query = SongLyric::search($args['search_string']);

        $song_lyrics_query->with('songbook_records');

        // $query = File::query();

		if (isset($args['filter_languages']) && is_array($args['filter_languages']))
            $song_lyrics_query = $song_lyrics_query->whereIn('language' , $args['filter_languages']);
            
        if (isset($args['filter_songbooks']) && is_array($args['filter_songbooks']))
            $song_lyrics_query = $song_lyrics_query->whereIn('')

		// if (isset($args['type']))
		// 	$query = $query->where('type', $args['type']);

		// if (isset($args['is_todo']) && $args['is_todo'])
		// 	$query = $query->todo();

        // return $query->get();
        

    }
}
