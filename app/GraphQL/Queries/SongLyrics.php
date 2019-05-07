<?php

namespace App\GraphQL\Queries;

use App\SongLyric;
use Log;

class SongLyrics
{
    public function resolve($rootValue, array $args)
    {
        $query = SongLyric::query();

		if (isset($args['search_string']))
			return SongLyric::search($args['search_string'])->get();
		
		if (isset($args['is_published']))
			$query = $query->where('is_published', $args['is_published']);

		if (isset($args['is_approved_by_author']))
			$query = $query->where('is_approved_by_author', $args['is_approved_by_author']);

		if (isset($args['has_lyrics']) && $args['has_lyrics'] === true)
			$query = $query->where('lyrics', '!=', '');
		if (isset($args['has_lyrics']) && $args['has_lyrics'] === false)
			$query = $query->where('lyrics', null);

		if (isset($args['has_authors']) && $args['has_authors'] === true)
			$query = $query->whereHas('authors');
		if (isset($args['has_authors']) && $args['has_authors'] === false)
			$query = $query->whereDoesntHave('authors');

		if (isset($args['has_tags']) && $args['has_tags'] === true)
			$query = $query->whereHas('tags');
		if (isset($args['has_tags']) && $args['has_tags'] === false)
			$query = $query->whereDoesntHave('tags');

		if (isset($args['has_chords']))
			$query = $query->where('has_chords', $args['has_chords']);

		if (isset($args['only_apk']) && $args['only_apk'] === true)
			$query = $query->where('is_approved_by_author', 1)->where('is_published', 1)->where('lyrics', '!=', '');

		if (isset($args['order_abc']))
            $query = $query->orderBy('name', 'asc');

        Log::info($query->toSql());

		return $query->get();
    }
}