<?php

namespace App\Http\Controllers;

use App\Author;
use App\Song;
use App\SongLyric;
use App\Video;

class PublicController extends Controller
{
    public function renderHome()
    {
        // Top songs
        $songs        = Song::all();
        
        // DANGEROUS TUPE MIXING
        $translations = SongLyric::where('is_original', 0)->get();
        $list         = $songs->concat($translations);
        $top_songs    = $list->sortByDesc('visits')->take(15);

        $translations_count = SongLyric::count();

        if ($translations_count) {
            $lyrics_percentage = SongLyric::where('lyrics', '!=', '')->count() / $translations_count * 100;
            $lyrics_percentage = floor($lyrics_percentage);
        } else {
            $lyrics_percentage = 0;
        }


        // Top authors
        $top_authors = Author::orderBy('visits', 'desc')->take(15)->get();

        return view('home', [
            'songs_count'             => Song::count(),
            'translations_count'      => SongLyric::where('is_original', 0)->count(),
            'authors_count'           => Author::count(),
            'videos_count'            => Video::count(),
            'lyrics_percentage' => $lyrics_percentage,
            'top_songs'         => $top_songs,
            'top_authors'       => $top_authors,
        ]);
    }
}
