<?php

namespace App\Http\Controllers;

use App\Author;
use App\SongLyric;
use App\External;

class PublicController extends Controller
{
    public function renderHome()
    {
        $top_songs   = SongLyric::orderBy('visits', 'desc')->where('lyrics', '!=', '')->take(15)->get();
        $top_authors = Author::orderBy('visits', 'desc')->take(15)->get();

        return view('home', [
            'songs_count'   => SongLyric::where('lyrics', '!=', '')->count(),
            'authors_count' => Author::count(),
            'videos_count'  => Video::count(),
            'top_songs'     => $top_songs,
            'top_authors'   => $top_authors,
        ]);
    }
}
