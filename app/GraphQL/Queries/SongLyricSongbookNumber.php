<?php

namespace App\GraphQL\Queries;

use App\SongLyric;

use App\Helpers\SongPart;
use Log;

class SongLyricSongbookNumber
{
	public function __invoke($rootValue, array $args)
	{
		$match_songbook_shortcut_lazy = "([\d\w]+?)";
		$match_number = "(\d+\w*)";

		preg_match("/$match_songbook_shortcut_lazy\s*$match_number/", $args['number'], $matches);

		if (count($matches) == 0) {
			return;
		}

		return SongLyric::whereHas('songbook_records', function ($q) use ($matches) {
			return $q->where('number', $matches[2])
				->where('shortcut', $matches[1]);
		})->first();
	}
}
