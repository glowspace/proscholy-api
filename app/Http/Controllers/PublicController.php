<?php

namespace App\Http\Controllers;

use App\Author;
use App\SongLyric;
use App\External;

class PublicController extends Controller
{
    public function renderHome()
    {
        $top_songs   = SongLyric::orderBy('visits', 'desc')->whereHas('lyrics')->take(15)->get();
        $top_authors = Author::orderBy('visits', 'desc')->take(15)->get();

        return view('home', [
            'songs_count'   => SongLyric::whereHas('lyrics')->count(),
            'authors_count' => Author::count(),
            'externals_count'  => External::count(),
            'top_songs'     => $top_songs,
            'top_authors'   => $top_authors,
        ]);
    }
}
