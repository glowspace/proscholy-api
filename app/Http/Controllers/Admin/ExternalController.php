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

    public function create()
    {
        return view('admin.external.create');
    }

    public function store(Request $request)
    {
        $external = External::create(['url' => $request->url]);
        // todo move to event
        // $external->update([
        //     'type' => $external->guessType()
        // ]);

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

        // handle the AUTHORS
        if ($request->assigned_authors !== NULL) {
            // old authors that had been saved in db - an ID is passed
            $saved_authors = Arr::where($request->assigned_authors, function ($value, $key) {
                return is_numeric($value);
            });
            $external->authors()->sync($saved_authors);
    
            // new authors to create - a string NAME is passed
            $new_authors = Arr::where($request->assigned_authors, function ($value, $key) {
                return !is_numeric($value);
            });
    
            // create new authors and associate to current external model
            foreach ($new_authors as $author) {
                $external->authors()->create(['name' => $author]);
            }
            $external->save();
        } else {
            $external->authors()->sync([]);
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
            'save_show_song' => isset($song_lyric) ? $song_lyric->public_url : route('admin.song.index'),
            'save_edit_song' => isset($song_lyric) ? route('admin.song.edit', $song_lyric) : route('admin.song.index'),
        ];

        return redirect($redirect_arr[$request->redirect]);
    }
}
