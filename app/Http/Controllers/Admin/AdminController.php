<?php

namespace App\Http\Controllers\Admin;

use App\Author;
use App\Http\Controllers\Controller;
use App\Song;
use App\SongbookRecord;
use App\SongLyric;
use App\External;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    public function renderDash()
    {
        return view('admin.dash', [
            'songs_count'        => SongLyric::count(),
            'songs_w_text_count' => SongLyric::where('lyrics', '!=', '')->count(),
            'authors_count'      => Author::count(),
            'externals_count'    => External::count(),
        ]);
    }

    /**
     * Stránka pro doplňování obsahu.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderTodo()
    {
        return view('admin.todo', [
            'externals'                     => External::where('author_id', null)->orWhere('song_lyric_id', null)->get(),
            'songs_w_author'                => SongLyric::whereDoesntHave('authors')->get(),
            'songbook_record_w_translation' => SongbookRecord::where('song_lyric_id', '')->get(),
            'song_lyrics_w_lyrics'          => SongLyric::where('lyrics', '=', null)->get(),
        ]);
    }

    // public function renderTodoRandom()
    // {
    //     $externals_w_author = External::where('author_id', '')->orWhere('song_lyric_id', '')->get();
    //     $songs_w_author     = Song::whereDoesntHave('authors')->get()->concat(SongLyric::whereDoesntHave
    //     ('authors')->get()->where('is_original', '0'))->shuffle();

    //     $songbook_record_w_translation = SongbookRecord::where('song_lyric_id', '')->get();
    //     $song_lyrics_w_lyrics          = SongLyric::where('lyrics', '')->get()->shuffle();

    //     if (
    //         $externals_w_author->count() == 0 && $songs_w_author->count() == 0 && $songbook_record_w_translation->count() == 0
    //         && $song_lyrics_w_lyrics->count() == 0
    //     )
    //     {
    //         return redirect()->route('admin.todo');
    //     }


    //     $rand = rand(1, 3);

    //     // Songy bez autora
    //     if ($rand == 1)
    //     {
    //         if ($songs_w_author->count() == 0)
    //         {
    //             return redirect()->route('admin.todo.random');
    //         }

    //         return view('admin.todo_editors.song_w_author', [
    //             'song'    => $songs_w_author->first(),
    //             'authors' => Author::all(),
    //         ]);
    //     }
    //     elseif ($rand == 2)
    //     {
    //         if ($songbook_record_w_translation->count() == 0)
    //         {
    //             return redirect()->route('admin.todo.random');
    //         }

    //         return view('admin.todo_editors.songbook_record_w_translation', [
    //             'record'       => $songbook_record_w_translation->first(),
    //             'translations' => SongLyric::all(),
    //         ]);
    //     }
    //     elseif ($rand == 4)
    //     {
    //         // Pokud už žádný není - hurá, vyhledáme něco jinýho.
    //         if ($song_lyrics_w_lyrics->count() == 0)
    //         {
    //             return redirect()->route('admin.todo.random');
    //         }

    //         return view('admin.todo_editors.translation_w_lyrics', ['translation' => $song_lyrics_w_lyrics->first()]);
    //     }
    //     else
    //     {
    //         if ($externals_w_author->count() == 0)
    //         {
    //             return redirect()->route('admin.todo.random');
    //         }

    //         return view('admin.todo_editors.external', ['external' => $externals_w_author->first()]);
    //     }
    // }
}
