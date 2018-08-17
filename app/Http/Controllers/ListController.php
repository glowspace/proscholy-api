<?php

namespace App\Http\Controllers;

use App\Author;
use App\Song;
use App\SongTranslation;

class ListController extends Controller
{
    public function renderSongListAlphabetical()
    {
        $songs        = Song::all();
        $translations = SongTranslation::all()->where('is_original',0);

        $list = $songs->concat($translations)->sortBy('name');

        return view('song_list', [
            'songs' => $list,
        ]);
    }

    public function renderAuthorListAlphabetical()
    {
        return view('author_list', [
            'authors' => Author::all()->sortBy('name'),
        ]);
    }
}
