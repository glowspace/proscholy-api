<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Song;
use App\SongLyric;

class SongController extends Controller
{
    public function __construct()
    {

    }

    public function index(){
        $song_lyrics = SongLyric::all();
        return view('admin.song.index', compact('song_lyrics'));
    }

    public function create(){
        return view('admin.song.create');
    }

    public function store(Request $request){
        // TODO: make this line working
        // SongLyrics::create($request->all());

        $song       = new Song();
        $song->name = $request['name'];
        $song->save();

        $song_l                = new SongLyric();
        $song_l->name          = $request['name'];
        $song_l->song_id       = $song->id;
        $song_l->saveOrFail();

        if ($request["redirect"] == "edit") {
            return redirect()->route('admin.song.edit', ['id' => $song_l->id]);
        }

        return redirect()->route('admin.song.create');
    }

    public function edit(SongLyric $song_lyric)
    {
        return view('admin.song.edit', compact('song_lyric'));
    }

    public function destroy(SongLyric $song_lyric){
        // TODO: find if a Song model that had been linked to this SongLyric has no dependencies anymore
        // in the case delete this one as well

        $song_lyric->delete();
    }

    public function update(Request $request, SongLyric $song_lyric)
    {
        $song_lyric->update($request->all());
        return redirect()->route('admin.song.index');
    }
}
