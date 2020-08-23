<?php

namespace App\Http\Controllers\Admin;

use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Author;
use App\Song;
use App\SongLyric;
use Illuminate\Support\Arr;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.form.index', [
            'model_name' => 'file',
            'title' => 'Nahrané soubory'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SongLyric $song_lyric = null)
    {
        return view('admin.file.create', compact('song_lyric'));
    }

    public function create_for_song(Request $request, SongLyric $song_lyric)
    {
        return $this->create($song_lyric);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $path = $request->file('filename')->store('public_files');
        $fullname = $request->file('filename')->getClientOriginalName();

        $filename = pathinfo($fullname, PATHINFO_FILENAME);
        $extension = pathinfo($fullname, PATHINFO_EXTENSION);

        $slugified = str_slug($filename, '-') . '.' . $extension;

        $type = 3; // default is sheet music

        if ($extension == "mp3") {
            $type = 4;
        }

        $file = File::create([
            'filename' => $slugified,
            'path' => $path,
            'type' => $type // set default to sheet music
        ]);

        if ($request->has('song_lyric_id')) {
            $file->song_lyric()->associate(Song::find($request->song_lyric_id));
            $file->save();
        }

        if ($request->redirect == 'create') {
            if ($request->has('song_lyric_id') && !empty($request->song_lyric_id)) {
                return redirect()->route('admin.file.create_for_song', $request->song_lyric_id);
            }

            return redirect()->route('admin.file.create');
        }

        return redirect()->route('admin.file.edit', $file);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, File $file)
    {
        return view('admin.form.edit', [
            'model_name' => 'file',
            'model_id' => $file->id,
            'title' => 'Soubor #' . $file->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        $file->update($request->all());

        // need to handle the checkbox
        if (!$request->has("has_anonymous_author")) {
            $file->has_anonymous_author = 0;
            $file->save();
        }

        // handle the AUTHORS
        if ($request->assigned_authors !== NULL) {
            // old authors that had been saved in db - an ID is passed
            $saved_authors = Arr::where($request->assigned_authors, function ($value, $key) {
                return is_numeric($value);
            });
            $file->authors()->sync($saved_authors);

            // new authors to create - a string NAME is passed
            $new_authors = Arr::where($request->assigned_authors, function ($value, $key) {
                return !is_numeric($value);
            });

            // create new authors and associate to current file model
            foreach ($new_authors as $author) {
                $file->authors()->create(['name' => $author]);
            }
            $file->save();
        } else {
            $file->authors()->sync([]);
            $file->save();
        }

        // no song lyric set, delete if there had been any association
        if ($request->assigned_song_lyrics == null) {
            $file->song_lyric()->dissociate();
            $file->save();
        } else {
            $song_lyric_identification = $request->assigned_song_lyrics[0];

            $song_lyric = SongLyric::getByIdOrCreateWithName($song_lyric_identification);

            $file->song_lyric()->associate($song_lyric);
            $file->save();
        }

        $redirect_arr = [
            'save' => route('admin.file.index'),
            'save_show_song' => isset($song_lyric) ? $song_lyric->public_url : route('admin.song.index'),
            'save_edit_song' => isset($song_lyric) ? route('admin.song.edit', $song_lyric) : route('admin.song.index')
        ];

        return redirect($redirect_arr[$request->redirect]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, File $file)
    {
        $file->delete();

        if ($request->has("redirect")) {
            return redirect($request->redirect);
        }

        return redirect()->back();
    }
}
