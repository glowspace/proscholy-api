<?php

namespace App\GraphQL\Queries;

use App\SongLyric;

class SongLyricPartsJson
{
	public function resolve($rootValue, array $args)
	{
		$slp = new SongLyricParts();

		$json = $slp->resolve($rootValue, $args);
		return compact('json');
	}
}
