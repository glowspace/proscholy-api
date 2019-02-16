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
        $song_l->is_authorized = 0;
        $song_l->is_original   = 0;
        // $song_l->lang          = 'cs';
        $song_l->saveOrFail();

        if ($request["redirect"] == "edit") {
            return redirect()->route('admin.song.edit', ['id' => $song_l->id]);
        }

        return redirect()->route('admin.song.create');
    }

    public function edit(SongLyric $song_lyric)
    {
        dd($song_lyric->name);
        
        return view('admin.song.edit', compact('song_l'));
    }

    public function destroy(SongLyric $song_l){
        // TODO: find if a Song model that had been linked to this SongLyric has no dependencies anymore
        // in the case delete this one as well

        $song_l->delete();
    }

    public function update(Request $request, SongLyric $song_l)
    {
        $song_l->update($request->all());
        return redirect()->route('admin.song.index');
    }
}
