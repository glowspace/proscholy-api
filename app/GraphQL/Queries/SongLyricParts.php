<?php

namespace App\GraphQL\Queries;

use App\SongLyric;

use App\Helpers\SongPart;
use Log;

class SongLyricParts
{
	public function resolve($rootValue, array $args)
	{
		$song_lyric = SongLyric::find($args['id']);

		return array_map(
			function ($part): array {
				return [
					'type' => $part->getType(),
					'isHidden' => $part->isHidden(),
					'isHiddenText' => $part->isHiddenText(),
					'isEmpty' => $part->isEmpty(),
					'isVerse' => $part->isVerse(),
					'isRefrain' => $part->isRefrain(),
					'isInline' => $part->isInline(),

					'songLines' => array_map(
						function ($line): array {
							return [
								'chords' => array_map(
									function ($chord): array {
										return [
											'base' => $chord->getBase(),
											'variant' => $chord->getVariant(),
											'extension' => $chord->getExtension(),
											'bass' => $chord->getBass(),
											'isSubstitute' => $chord->isSubstitute(),
											'isOptional' => $chord->isOptional(),
											'isDivided' => $chord->isDivided(),
											'text' => $chord->getText(),
										];
									},
									$line->getChords()
								),
								'is_comment' => $line->getIsComment()
							];
						},
						$part->getSongLines()
					),
				];
			},
			$song_lyric->getSongParts()
		);
	}
}
