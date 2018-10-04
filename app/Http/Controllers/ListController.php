<?php

namespace App\Http\Controllers;

use App\Author;
use App\Song;
use App\SongLyric;

class ListController extends Controller
{
    public function renderSongListAlphabetical()
    {
        $songs        = Song::all();
        $translations = SongLyric::where('is_original',0)->get();

        $list = $songs->concat($translations)->sortBy('name');

        return view('song_list', [
            'songs' => $list,
        ]);
    }

    public function renderAuthorListAlphabetical()
    {
        return view('author_list', [
            'authors' => Author::orderBy('name')->get(),
        ]);
    }
}
