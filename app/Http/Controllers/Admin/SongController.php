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
        $song_lyrics = SongLyric::orderBy('created_at', 'desc')->paginate(100);
        return view('admin.song.index', compact('song_lyrics'));
    }

    public function create(){
        return view('admin.song.create');
    }

    public function store(Request $request)
    {
        $song_l = SongLyric::getByIdOrCreateWithName($request->name);

        $redirect_arr = [
            'edit' => route('admin.song.edit', ['id' => $song_l->id]),
            'create' => route('admin.song.create')
        ];

        return redirect($redirect_arr[$request->redirect]);
    }

    public function edit(SongLyric $song_lyric)
    {
        $assigned_authors = $song_lyric->authors()->select(['authors.id', 'authors.name'])->get();
        $all_authors = Author::select(['id', 'name'])->orderBy('name')->get();

        $domestic_song_lyric = $song_lyric->song->getNonCuckooSongLyric($song_lyric->id);
        // prepare a singleton array for the magicsuggest component
        $assigned_song_lyrics = $domestic_song_lyric ? [$domestic_song_lyric] : [];
        $all_song_lyrics = SongLyric::select(['id', 'name'])->where('id', '!=', $song_lyric->id)->get();
        // do not allow to change the field in this situation.. trust me it's complicated
        $assigned_song_disabled = $song_lyric->hasSiblings() && $song_lyric->isDomestic();

        return view('admin.song.edit', compact(
            'song_lyric', 
            'assigned_authors', 'all_authors',
            'assigned_song_lyrics', 'all_song_lyrics', 'assigned_song_disabled'));
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

    public function update(Request $request, SongLyric $song_lyric)
    {
        // name has changed ???
        if ($request->name !== $song_lyric->name) {
            // preserve the invariant - if it had the same name before - it's domestic, then it must stay so!!
            if ($song_lyric->isDomestic()) {
                $song_lyric->song->name = $request->name;
                $song_lyric->song->save();
            }
        }
        $song_lyric->update($request->all());

        if ($request->authors !== NULL) {
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
            $song_lyric->save();
        }

        if ($request->assigned_song_lyrics === NULL && $song_lyric->isCuckoo()) {
            // this means I was a cuckoo so I need to find a new parent,
            // that is gonna have a same name as me, so I'm not gonna be a cockoo anymore :P
            $new_parent = Song::create(['name' => $song_lyric->name]);
            $song_lyric->song()->associate($new_parent);
            $song_lyric->save();
        }

        if ($request->assigned_song_lyrics !== NULL && ($song_lyric->isCuckoo() || $song_lyric->isDomesticOrphan())) {
            $identificator = $request->assigned_song_lyrics[0];

            $friend = SongLyric::getByIdOrCreateWithName($identificator);
            // this song is supposed to be an original
            $friend->is_original = 1;
            $friend->save();
            // associate to the friends Song and stay/become a Cuckoo :) :O
            $song_lyric->song()->associate($friend->song);
            $song_lyric->save();
        }

        $redirect_arr = [
            'save' => route('admin.song.index'),
            'add_external' => route('admin.external.create_for_song', ['song_lyric' => $song_lyric->id]),
            'save_show' => route('client.song.text', ['song_id' => $song_lyric->id])
        ];

        // check if there is any bad behaviour
        
        // 1. case: there is a group of SongLyrics under one Song that have no original
        if ($song_lyric->hasSiblings() &&
            $song_lyric->song->getOriginalLyric() === NULL)
        {
            return view('admin.song.error', [
                'error' => Song::ERR_NO_ORIGINAL,
                'song' => $song_lyric->song 
            ]);
        }

        // 2. case: there is more originals in one group
        if ($song_lyric->song->song_lyrics()->where('is_original', 1)->count() > 1)
        {
            return view('admin.song.error', [
                'error' => Song::ERR_MORE_ORIGINALS,
                'song' => $song_lyric->song 
            ]);
        }

        return redirect($redirect_arr[$request->redirect]);

        // return redirect()->route('admin.song.index');
    }

    public function resolve_error(Request $request, Song $song)
    {
        if ($request->solution === 'choose_original') {
            // set this id as the original
            $id_orig = $request->song_original;

            foreach ($song->song_lyrics as $song_l) {
                $song_l->is_original = $song_l->id == $id_orig;
                $song_l->save();
            }

            return redirect()->route('admin.song.index');
        }
        else if ($request->solution === 'create_original') {
            $song_lyric = SongLyric::create([
                'name' => '',
                'song_id' => $song->id,
                'is_original' => 1
            ]);
            $song_lyric->save();
            // change name of the parent Song model
            $song->name = '';
            $song->save();

            return redirect()->route('admin.song.edit', $song_lyric);
        }
        else if ($request->solution === 'keep') {
            // don't do anything
            return redirect()->route('admin.song.index');
        }

    }
}
