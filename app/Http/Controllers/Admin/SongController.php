<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use App\Song;
use App\SongLyric;
use App\Author;

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
        $assigned_authors = $song_lyric->authors()->select(['authors.id', 'authors.name'])->get();
        $all_authors = Author::select(['id', 'name'])->get();

        return view('admin.song.edit', compact('song_lyric', 'assigned_authors', 'all_authors'));
    }

    public function destroy(SongLyric $song_lyric){
        // TODO: find if a Song model that had been linked to this SongLyric has no dependencies anymore
        // in the case delete this one as well

        $song_lyric->delete();
    }

    public function update(Request $request, SongLyric $song_lyric)
    {
        // dd($request->authors);
        $song_lyric->update($request->all());

        // old authors that had been saved in db - an ID is passed
        $saved_authors = Arr::where($request->authors, function ($value, $key) {
            return is_numeric($value);
        });
        $song_lyric->authors()->sync($saved_authors);

        // new authors to create - a NAME is passed
        $new_authors = Arr::where($request->authors, function ($value, $key) {
            return !is_numeric($value);
        });

        // create new authors and associate to current song_lyric model
        foreach ($new_authors as $author) {
            $song_lyric->authors()->create(['name' => $author]);
        }

        return redirect()->route('admin.song.index');
    }
}
