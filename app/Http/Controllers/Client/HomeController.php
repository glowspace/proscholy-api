<?php

namespace App\Http\Controllers\Client;

use App\Author;
use App\Http\Controllers\Controller;
use App\SongLyric;
use App\External;

class HomeController extends Controller
{
    public function renderHome()
    {
        $top_songs   = SongLyric::orderBy('visits', 'desc')->where('lyrics', '!=', '')->take(7)->get();
        $top_authors = Author::orderBy('visits', 'desc')->take(15)->get();

        return view(' client.home', [
            'song_count'   => SongLyric::where('lyrics', '!=', '')->count(),
            'authors_count' => Author::count(),
            'externals_count'  => External::count(),
            'top_songs'     => $top_songs,
            'top_authors'   => $top_authors,
        ]);
    }

    public function renderAboutTeam()
    {
        return view('client.about_team');
    }
}
