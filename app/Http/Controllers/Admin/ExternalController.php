<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\External;
use App\Author;
use App\Song;
use App\SongLyric;

class ExternalController extends Controller
{
    public function __construct()
    {

    }

    public function index(){
        $todo = External::whereDoesntHave('author')->orWhereDoesntHave('song_lyric')->get();
        $rest = External::whereHas('author')->whereHas('song_lyric')->get();

        return view('admin.external.index', compact('todo', 'rest'));
    }

    public function create(){
        return view('admin.external.create');
    }

    public function store(Request $request){
        // TODO: make this line working
        // Externals::create($request->all());

        $external       = new External();
        $external->type = $request["type"];
        $external->url = $request['url'];
        $external->save();

        if ($request["redirect"] == "edit") {
            return redirect()->route('admin.external.edit', ['id' => $external->id]);
        }

        return redirect()->route('admin.external.create');
    }

    public function edit(External $external)
    {
        // this field needs to be saved as a singleton array or empty array
        // if passed just as [$external->author] then the result is [{}] if there is nothing
        $assigned_authors = $external->author ? [$external->author] : [];
        $all_authors = Author::select(['id', 'name'])->get();

        $assigned_song_lyrics = $external->song_lyric ? [$external->song_lyric] : [];
        $all_song_lyrics = SongLyric::select(['id', 'name'])->get();

        return view('admin.external.edit', compact(
            'external', 
            'assigned_authors', 'all_authors',
            'assigned_song_lyrics', 'all_song_lyrics'
        ));
    }

    public function destroy(External $external){
        // TODO: find if a External model that had been linked to this External has no dependencies anymore
        // in the case delete this one as well

        $external->delete();
    }

    public function update(Request $request, External $external)
    {
        $external->update($request->all());

        // no author set, delete if there had been any association
        if ($request->assigned_authors === NULL) {
            $external->author()->dissociate();
            $external->save();
        }
        else {
            $author_identification = $request->assigned_authors[0];

            $author;
            
            if (is_numeric($author_identification)) {
                // ID was given, find an "old" author
                $author = Author::find($author_identification);
            } else {
                // create a new talented author
                $author = Author::create(['name' => $author_identification]);
            }

            $external->author()->associate($author);
            $external->save();
        }

        // TODO: enable add new one???????????????????????????????????????????????????????//

        // no song lyric set, delete if there had been any association
        if ($request->assigned_song_lyrics == NULL) {
            $external->song_lyric()->dissociate();
            $external->save();
        }
        else {
            $song_lyric_identification = $request->assigned_song_lyrics[0];

            $song_lyric;
            
            if (is_numeric($song_lyric_identification)) {
                // ID was given, find an "old" song_lyric
                $song_lyric = SongLyric::find($song_lyric_identification);
            } else {
                // create a Song model and then an associated SongLyric
                $song       = new Song();
                $song->name = $song_lyric_identification;
                $song->save();

                $song_lyric = SongLyric::create([
                    'name' => $song_lyric_identification,
                    'song_id' => $song->id
                ]);
            }

            $external->song_lyric()->associate($song_lyric);
            $external->save();
        }

        return redirect()->route('admin.external.index');
    }
}
