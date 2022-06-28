<?php

namespace App\GraphQL\Queries;

use App\SongLyric;

class SongLyricPartsJson
{
	public function __invoke($rootValue, array $args)
	{
		$slp = new SongLyricParts();

		$json = $slp->__invoke($rootValue, $args);
		return compact('json');
	}
}
