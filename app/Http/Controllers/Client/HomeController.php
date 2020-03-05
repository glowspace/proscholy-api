<?php

namespace App\Http\Controllers\Client;

use App\Author;
use App\Http\Controllers\Controller;
use App\SongLyric;
use App\External;

class HomeController extends Controller
{
    public function renderSpa()
    {
        return view('client.spa');

        /*
        $top_songs   = SongLyric::orderBy('visits', 'desc')->where('lyrics', '!=', '')->take(7)->get();
        $top_authors = Author::orderBy('visits', 'desc')->take(15)->get();

        return view(' client.home', [
            'song_count'   => SongLyric::notEmpty()->count(),
            'authors_count' => Author::count(),
            'externals_count'  => External::count(),
            'top_songs'     => $top_songs,
            'top_authors'   => $top_authors,
        ]);
        */
    }
}
