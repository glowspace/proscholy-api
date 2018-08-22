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

        if($song->authors()->count() > 0)
        {
            foreach ($song->authors as $author)
            {
                $author->visits = $author->visits + 1;
                $author->save();
            }

        }

        return view('song', [
            'song' => $song,
            'page_title'  => $song->name,
        ]);
    }

}
