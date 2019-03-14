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
        // comment_chat_for_fun_initialize:

        // Mira: zdar jak sviňa Michale, jak sa vede? :D
        // Michal: Dá se, už zobrazujeme překlady u písně. :-) Měl bych se mrknout na trello. :D
        // Mira: jj, já jsem teďkom pořád tak trochu mimo net, takže v podstatě jen pulluju/pushuju git přes data,
        //       abych viděl, co se děje :D
        // Michal: :D, já myslím, že slack zas tolik dat naukrojí, jsou to jen texty. :D
        // Mira: zdravím z letadla :) (commit z letadla jen tak někdo nemá :D)
    }

    public function index()
    {
        $externals = External::all();

        return view('admin.external.index', compact('externals'));
    }

    public function todoAuthors(){
        $externals = External::where('author_id', null)->where('has_anonymous_author', 0)
            ->orWhere('song_lyric_id', null)
            ->get();

        $title = "Seznam externích odkazů bez přiřazeného autora nebo písně";
        return view('admin.external.index', compact('externals', 'title'));
    }

    public function create()
    {
        return view('admin.external.create');
    }

    public function store(Request $request)
    {
        $external = External::create(['url' => $request->url]);

        // TODO: try to guess the type according to url

        $redirect_arr = [
            'edit'   => route('admin.external.edit', ['id' => $external->id]),
            'create' => route('admin.external.create'),
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
        $all_authors      = Author::select(['id', 'name'])->orderBy('name')->get();

        $assigned_song_lyrics = $external->song_lyric ? [$external->song_lyric] : [];
        $all_song_lyrics      = SongLyric::select(['id', 'name'])->orderBy('name')->get();

        return view('admin.external.edit', compact(
            'external',
            'assigned_authors', 'all_authors',
            'assigned_song_lyrics', 'all_song_lyrics'
        ));
    }

    public function destroy(Request $request, External $external)
    {
        $external->delete();

        if ($request->has("redirect"))
        {
            return redirect($request->redirect);
        }

        return redirect()->back();
    }

    public function update(Request $request, External $external)
    {
        $external->update($request->all());

        // need to handle the checkbox
        if (!$request->has("has_anonymous_author")) {
            $external->has_anonymous_author = 0;
            $external->save();
        }

        // need to handle the checkbox
        if (!$request->has("is_featured")) {
            $external->is_featured = 0;
            $external->save();
        }

        // no author set, delete if there had been any association
        if ($request->assigned_authors === null)
        {
            $external->author()->dissociate();
            $external->save();
        }
        else
        {
            $author_identification = $request->assigned_authors[0];
            $author = Author::getByIdOrCreateWithName($author_identification);

            $external->author()->associate($author);
            $external->save();
        }

        // no song lyric set, delete if there had been any association
        if ($request->assigned_song_lyrics == null)
        {
            $external->song_lyric()->dissociate();
            $external->save();
        }
        else
        {
            $song_lyric_identification = $request->assigned_song_lyrics[0];

            $song_lyric = SongLyric::getByIdOrCreateWithName($song_lyric_identification);

            $external->song_lyric()->associate($song_lyric);
            $external->save();
        }

        // no error => continue with redirecting according to a selected action
        $redirect_arr = [
            'save' => route('admin.external.index'),
            'save_show_song' => isset($song_lyric) ? route('client.song.text', $song_lyric) : route('admin.song.index'),
        ];

        return redirect($redirect_arr[$request->redirect]);
    }
}
