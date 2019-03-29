<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

use App\SongLyric;

class SongLyricsQuery extends Query {

	protected $attributes = [
		'name' => 'song_lyrics',
		'description' => 'A Query for the SongLyric model, without args returns all available SongLyrics. 
		Use args is_published:true, is_approved_by_author:true and has_lyrics:true for use in apk'
	];

	public function type()
	{
		return Type::listOf(GraphQL::type('song_lyric'));
	}

	public function args()
	{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int()
			],
			'is_published' => [
				'name' => 'is_published',
				'type' => Type::boolean(),
				'description' => 'Whether SongLyric has been reviewed by editors'
			],
			'is_approved_by_author' => [
				'name' => 'is_approved_by_author',
				'type' => Type::boolean(),
				'description' => 'Whether SongLyric has been approved by its author for public use'
			],
			'has_lyrics' => [
				'name' => 'has_lyrics',
				'type' => Type::boolean(),
				'description' => 'Whether SongLyric has some lyrics'
			],
			'only_apk' => [
				'name' => 'only_apk',
				'type' => Type::boolean(),
				'description' => 'Shortcut - wheter SongLyric is to be used in apk. Only for use with only_apk:true'
			]
		];
	}

	public function resolve($root, $args)
	{
		$query = SongLyric::query();
		
		if (isset($args['is_published']))
			$query = $query->where('is_published', $args['is_published']);

		if (isset($args['is_approved_by_author']))
			$query = $query->where('is_approved_by_author', $args['is_approved_by_author']);

		if (isset($args['has_lyrics']) && $args['has_lyrics'] === true)
			$query = $query->where('lyrics', '!=', '');
		if (isset($args['has_lyrics']) && $args['has_lyrics'] === false)
			$query = $query->where('lyrics', null);

		if (isset($args['only_apk']) && $args['only_apk'] === true)
			$query = $query->where('is_approved_by_author', 1)->where('is_published', 1)->where('lyrics', '!=', '');

		return $query->get();
	}
}