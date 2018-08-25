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
        $translations = SongTranslation::where('is_original', 0)->get();
        $list         = $songs->concat($translations);
        $top_songs    = $list->sortByDesc('visits')->take(15);

        $translations_count = SongTranslation::count();

        if ($translations_count) {
            $lyrics_percentage = SongTranslation::where('lyrics', '!=', '')->count() / $translations_count * 100;
            $lyrics_percentage = floor($lyrics_percentage);
        } else {
            $lyrics_percentage = 0;
        }


        // Top authors

        // TODO: refactor to use eloquent instead of array manipulation
        $top_authors = Author::all()->sortByDesc('visits')->take(15);

        return view('home', [
            'songs_count'             => Song::count(),
            'translations_count'      => SongTranslation::where('is_original', 0)->count(),
            'authors_count'           => Author::count(),
            'videos_count'            => Video::count(),
            'lyrics_percentage' => $lyrics_percentage,
            'top_songs'         => $top_songs,
            'top_authors'       => $top_authors,
        ]);
    }
}
