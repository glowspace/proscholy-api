<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Song;
use App\SongLyric;
use App\Author;
use App\Tag;

class SongController extends Controller
{
    private function apply_request_filters($query, Request $request)
    {
        $res_query = $query;
        if ($request->has('filter_author_id')) {
            $res_query = $res_query->whereHas('authors', function($q) use ($request) {
                $q->where('authors.id', $request['filter_author_id']);
            });
        }

        return $res_query;
    }

    public function index()
    {
        return view('admin.song.index', ['type' => 'list-all']);
    }

    public function todoLyrics()
    {
        return view('admin.song.index', ['type' => 'todo-lyrics']);
    }

    public function todoAuthors() 
    {
        return view('admin.song.index', ['type' => 'todo-authors']);
    }

    public function todoChords()
    {
        return view('admin.song.index', ['type' => 'todo-chords']);
    }

    public function todoTags() {
        return view('admin.song.index', ['type' => 'todo-tags']);
    }

    public function todoPublish(Request $request) {
        $query = SongLyric::where('is_published', 0)->orderBy('name');
        $song_lyrics = $this->apply_request_filters($query, $request)->get();

        $title = "Seznam písní k publikování";
        return view('admin.song.index', compact('song_lyrics', 'title'));
    }

    // author account todo
    public function todoApprove(Request $request)
    {
        $query = SongLyric::restricted()->where('is_approved_by_author', 0)->orderBy('name');
        $song_lyrics = $this->apply_request_filters($query, $request)->get();

        $title = "Seznam písní ke schválení autorem";
        return view('admin.song.index', compact('song_lyrics', 'title'));
    }
    
    public function edit(SongLyric $song_lyric)
    {
        if ($song_lyric->isLocked()) {
            return view('admin.lockerror', compact('song_lyric'));
        }

        $song_lyric->lock();
        return view('admin.song.edit', compact('song_lyric'));
    }

    // public function destroy(Request $request, SongLyric $song_lyric)
    // {
    //     // TODO: find if a Song model that had been linked to this SongLyric has no dependencies anymore
    //     // in the case delete this one as well

    //     $song_lyric->delete();

    //     if ($request->has("redirect")) {
    //         return redirect($request->redirect);
    //     }

    //     return redirect()->back();
    // }
}
