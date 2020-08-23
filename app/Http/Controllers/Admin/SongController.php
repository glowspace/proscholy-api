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
            $res_query = $res_query->whereHas('authors', function ($q) use ($request) {
                $q->where('authors.id', $request['filter_author_id']);
            });
        }

        return $res_query;
    }

    public function index()
    {
        return view('admin.form.index', [
            'model_name' => 'song',
            'title' => 'Písně'
        ]);
    }

    public function edit(Request $request, SongLyric $song_lyric)
    {
        return view('admin.form.edit', [
            'model_name' => 'song-lyric',
            'model_id' => $song_lyric->id,
            'title' => 'Píseň ' . $song_lyric->name
        ]);
    }

    public function destroy(Request $request, SongLyric $song_lyric)
    {
        // TODO: find if a Song model that had been linked to this SongLyric has no dependencies anymore
        // in the case delete this one as well

        $song_lyric->delete();

        if ($request->has("redirect")) {
            return redirect($request->redirect);
        }

        return redirect()->back();
    }
}
