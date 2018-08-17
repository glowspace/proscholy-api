<?php

namespace App\Http\Controllers;


use App\Song;
use App\SongTranslation;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function renderSong($id)
    {
        $song         = Song::findOrFail($id);
        $song->visits = $song->visits + 1;
        $song->save();

        return view('song', [
            'song' => $song,
            'page_title'  => $song->name,
        ]);
    }

}
