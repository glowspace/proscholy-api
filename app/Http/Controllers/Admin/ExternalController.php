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
        $todo = External::whereDoesntHave('author')->orWhereDoesntHave('song_lyric')->orderBy('created_at', 'desc')->get();
        $rest = External::whereHas('author')->whereHas('song_lyric')->orderBy('created_at', 'desc')->get();

        return view('admin.external.index', compact('todo', 'rest'));
    }

    public function create(){
        return view('admin.external.create');
    }
    
    public function store(Request $request)
    {
        $external = External::create(['url' => $request->url]);

        $redirect_arr = [
            'edit' => route('admin.external.edit', ['id' => $external->id]),
            'create' => route('admin.external.create')
        ];

        return redirect($redirect_arr[$request->redirect]);
    }

    public function create_for_song(Request $request, SongLyric $song_lyric)
    {
        // shortcut for directly editing with an empty url and an assigned song_lyric
        $external = External::create();
        $external->song_lyric()->associate($song_lyric);

        return $this->edit($request, $external);
    }

    public function edit(Request $request, External $external)
    {
        // this field needs to be saved as a singleton array or empty array
        // if passed just as [$external->author] then the result is [{}] if there is nothing
        $assigned_authors = $external->author ? [$external->author] : [];
        $all_authors = Author::select(['id', 'name'])->orderBy('name')->get();

        $assigned_song_lyrics = $external->song_lyric ? [$external->song_lyric] : [];
        $all_song_lyrics = SongLyric::select(['id', 'name'])->orderBy('name')->get();

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

        return redirect()->back();
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

            $song_lyric = SongLyric::getByIdOrCreateWithName($song_lyric_identification);

            $external->song_lyric()->associate($song_lyric);
            $external->save();
        }

        return redirect()->route('admin.external.index');
    }
}
