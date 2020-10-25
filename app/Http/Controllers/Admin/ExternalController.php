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
    // comment_chat_for_fun_initialize:

    // Mira: zdar jak sviňa Michale, jak sa vede? :D
    // Michal: Dá se, už zobrazujeme překlady u písně. :-) Měl bych se mrknout na trello. :D
    // Mira: jj, já jsem teďkom pořád tak trochu mimo net, takže v podstatě jen pulluju/pushuju git přes data,
    //       abych viděl, co se děje :D
    // Michal: :D, já myslím, že slack zas tolik dat naukrojí, jsou to jen texty. :D
    // Mira: zdravím z letadla :) (commit z letadla jen tak někdo nemá :D)
    // Mira: btw pro spusteni unit testu: vendor/bin/phpunit

    public function index()
    {
        return view('admin.form.index', [
            'model_name' => 'external',
            'title' => 'Materiály'
        ]);
    }

    public function edit(Request $request, External $external)
    {
        return view('admin.form.edit', [
            'model_name' => 'external',
            'model_id' => $external->id,
            'title' => 'Materiál #' . $external->id
        ]);
    }

    public function create_for_song(Request $request, SongLyric $song_lyric)
    {
        // shortcut for directly editing with an empty url and an assigned song_lyric
        $external = External::create();
        $external->song_lyric()->associate($song_lyric);
        $external->save();

        return $this->edit($request, $external);
    }
}
