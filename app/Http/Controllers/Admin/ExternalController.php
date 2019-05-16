<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\External;
use App\Author;
use App\Song;
use App\SongLyric;
use Illuminate\Support\Arr;

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
        // Mira: btw pro spusteni unit testu: vendor/bin/phpunit
    }

    public function index()
    {
        return view('admin.external.index', ['type' => 'show-all']);
    }

    public function todoAuthors(){
        $type = 'show-todo';

        $title = "Seznam externích odkazů bez přiřazeného autora nebo písně";
        return view('admin.external.index', compact('type', 'title'));
    }

    public function create_for_song(Request $request, SongLyric $song_lyric)
    {
        // shortcut for directly editing with an empty url and an assigned song_lyric
        $external = External::create();
        $external->song_lyric()->associate($song_lyric);
        $external->save();

        return $this->edit($request, $external);
    }

    public function edit(Request $request, External $external)
    {
        $assigned_authors = $external->authors;
        $all_authors      = Author::select(['id', 'name'])->orderBy('name')->get();
        
        // this field needs to be saved as a singleton array or empty array
        // if passed just as [$external->song_lyric] then the result is [{}] if there is nothing
        $assigned_song_lyrics = $external->song_lyric ? [$external->song_lyric] : [];
        $all_song_lyrics      = SongLyric::restricted()->select(['id', 'name'])->orderBy('name')->get();

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
}
