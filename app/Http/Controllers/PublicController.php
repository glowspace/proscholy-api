<?php

namespace App\Http\Controllers;

use App\Author;
use App\Song;
use App\SongTranslation;
use App\Video;

class PublicController extends Controller
{
    public function renderHome()
    {
        // Top songs
        $songs        = Song::all();
        $translations = SongTranslation::all()->where('is_original', 0);
        $list         = $songs->concat($translations);
        $top_songs    = $list->sortByDesc('visits')->take(15);

        $lyrics_percentage = SongTranslation::where('lyrics', '!=', '')->count() / SongTranslation::all()->count() * 100;
        $lyrics_percentage = floor($lyrics_percentage);


        // Top authors
        $top_authors = Author::all()->sortByDesc('visits')->take(15);

        return view('home', [
            'songs'             => Song::all()->count(),
            'translations'      => SongTranslation::all()->where('is_original', 0)->count(),
            'authors'           => Author::all()->count(),
            'videos'            => Video::all()->count(),
            'lyrics_percentage' => $lyrics_percentage,
            'top_songs'         => $top_songs,
            'top_authors'       => $top_authors,
        ]);
    }
}
