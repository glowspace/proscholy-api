<?php

namespace App\Http\Controllers;

use App\Author;
use App\Song;
use App\SongLyric;
use App\Video;

class PublicController extends Controller
{
    public function renderHome()
    {
        $top_songs = SongLyric::orderBy('visits', 'desc')->take(15)->get();
        $top_authors = Author::orderBy('visits', 'desc')->take(15)->get();

        return view('home', [
            'songs_count'             => Song::count(),
            // 'translations_count'      => SongLyric::where('is_original', 0)->count(),
            'authors_count'           => Author::count(),
            'videos_count'            => Video::count(),
            // 'lyrics_percentage' => $lyrics_percentage,
            'top_songs'         => $top_songs,
            'top_authors'       => $top_authors,
        ]);
    }
}
