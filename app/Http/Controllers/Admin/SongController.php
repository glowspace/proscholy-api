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
        $all_authors = Author::select(['id', 'name'])->orderBy('name')->get();

        $domestic_song_lyric = $song_lyric->song->getNonCuckooSongLyric($song_lyric->id);
        $assigned_song_lyrics = $domestic_song_lyric ? [$domestic_song_lyric] : [];
        $all_song_lyrics = SongLyric::select(['id', 'name'])->where('id', '!=', $song_lyric->id)->get();
        // do not allow to change the field in this situation.. trust me it's complicated
        $assigned_song_disabled = $song_lyric->hasSiblings() && $song_lyric->isDomestic();

        return view('admin.song.edit', compact(
            'song_lyric', 
            'assigned_authors', 'all_authors',
            'assigned_song_lyrics', 'all_song_lyrics', 'assigned_song_disabled'));
    }

    public function destroy(SongLyric $song_lyric){
        // TODO: find if a Song model that had been linked to this SongLyric has no dependencies anymore
        // in the case delete this one as well

        $song_lyric->delete();

        return redirect()->back();
    }

    public function update(Request $request, SongLyric $song_lyric)
    {
        $song_lyric->update($request->all());
        // name has changed ???
        if ($request->name !== $song_lyric->name) {
            // preserve the invariant - if it had the same name before - it's domestic, then it must stay so!!
            if ($song_lyric->isDomestic()) {
                $song_lyric->song->name = $request->name;
                $song_lyric->song->save();
            }
        }

        if ($request->assigned_song_lyrics === NULL && $song_lyric->isCuckoo()) {
            // this means I was a cuckoo so I need to find a new parent,
            // that is gonna have a same name as me, so I'm not gonna be a cockoo anymore :P
            $new_parent = Song::create(['name' => $song_lyric->name]);
            $song_lyric->song()->associate($song_lyric);
            $song_lyric->save();
        }

        if ($request->assigned_song_lyrics !== NULL && ($song_lyric->isCuckoo() || $song_lyric->isDomesticOrphan())) {
            $identificator = $request->assigned_song_lyrics[0];

            $friend = SongLyric::getByIdOrCreateWithName($identificator);
            // associate to the friends Song and stay/become a Cuckoo :) :O
            $song_lyric->song()->associate($friend->song);
            $song_lyric->save();
        }

        // if ($request->dominant_song_lyric !== NULL) {
        //     $identification = $request->dominant_song_lyric[0];
            
        //     $song;

        //     if (is_numeric($identification)) {
        //     // get an ID of the abstract Song model
        //         $song_id = SongLyric::find($request->dominant_song_lyric[0])->song->id;
        //         $song = Song::find($song_id);
        //     } else {
        //         $song = Song::create(['name' => $song_lyric->name]);
        //     }

        //     if ($song->id !== $song_lyric->song->id && $song->name !== $song_lyric->song->name) {
        //         // here is the old Song base model that is to be unlinked
        //         // if there is no more connection, then delete it from the db
        //         $old_song = Song::find($song_lyric->song->id);
        //         if ($old_song->song_lyrics()->count() === 1) {
        //             Song::destroy($song_lyric->song->id);
        //         }

        //         // update the changed parent Song
        //         $song_lyric->song()->associate($song);
        //         $song_lyric->save();
        //     }
        // } else {
        //     dd("fired");
        //     // reassociate to new Song just for you! :)
        //     if ($song_lyric->name !== $song_lyric->song->name) {
        //         $new_song = Song::create(['name' => $song_lyric->name]);
        //         $song_lyric->song()->associate($new_song);
        //         $song_lyric->save();
        //     }
        // }

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
        }

        return redirect()->route('admin.song.index');
    }
}
