<?php

namespace App\Http\Controllers\Admin;

use App\Author;
use App\Http\Controllers\Controller;
use App\Song;
use App\SongbookRecord;
use App\SongLyric;
use App\External;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    public function renderDash()
    {
        return view('admin.dash', [
            'songs_count'        => SongLyric::count(),
            'songs_w_text_count' => SongLyric::where('lyrics', '!=', '')->count(),
            'songs_w_all_count' => SongLyric::where('lyrics', '!=', '')
                ->where('has_chords', '=', true)
                ->where(function($query) {
                    $query->whereHas('authors', null)->orWhere('has_anonymous_author', 1);
                })->whereHas('tags')->count(),
            'songs_w_just_title_count' => SongLyric::whereNull('lyrics')
                ->where(function($query) {
                    $query->whereDoesntHave('authors', null)->where('has_anonymous_author', 0);
                })
                ->whereDoesntHave('tags', null)
                ->whereDoesntHave('songbook_records', null)
                ->whereDoesntHave('files', null)
                ->whereDoesntHave('externals')->count(),
            'authors_count'      => Author::count(),
            'externals_count'    => External::count(),
        ]);
    }

    /**
     * Stránka pro doplňování obsahu.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    // public function renderTodo()
    // {
    //     return view('admin.todo', [
    //         'externals'                     => External::where('author_id', null)->where('has_anonymous_author', 0)->orWhere('song_lyric_id', null)->get(),
    //         'songs_w_author'                => SongLyric::whereDoesntHave('authors')->where('has_anonymous_author', 0)->get(),
    //         'songbook_record_w_translation' => SongbookRecord::where('song_lyric_id', '')->get(),
    //         'song_lyrics_w_lyrics'          => SongLyric::where('lyrics', '=', null)->get(),
    //         'files'                         => File::where('author_id', null)->where('has_anonymous_author', 0)->get()
    //     ]);
    // }
}
