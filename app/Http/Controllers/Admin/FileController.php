<?php

namespace App\Http\Controllers\Admin;

use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Author;
use App\Song;
use App\SongLyric;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $files = File::all();
        return view('admin.file.index', compact('files'));
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
        
        $slugified = str_slug($filename, '-').'.'.$extension;

        $file = File::create([
            'filename' => $slugified,
            'path' => $path
        ]);

        if ($request->has('song_lyric_id')) {
            $file->song_lyric()->associate(Song::find($request->song_lyric_id));
            $file->save();
        }

        return redirect()->route('admin.file.edit', $file);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        $assigned_authors = $file->author ? [$file->author] : [];
        $all_authors      = Author::select(['id', 'name'])->orderBy('name')->get();

        $assigned_song_lyrics = $file->song_lyric ? [$file->song_lyric] : [];
        $all_song_lyrics      = SongLyric::select(['id', 'name'])->orderBy('name')->get();

        return view('admin.file.edit', compact(
            'file',
            'assigned_authors', 'all_authors',
            'assigned_song_lyrics', 'all_song_lyrics'
        ));
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

        // no author set, delete if there had been any association
        if ($request->assigned_authors === null)
        {
            $file->author()->dissociate();
            $file->save();
        }
        else
        {
            $author_identification = $request->assigned_authors[0];
            $author = Author::getByIdOrCreateWithName($author_identification);

            $file->author()->associate($author);
            $file->save();
        }

        // no song lyric set, delete if there had been any association
        if ($request->assigned_song_lyrics == null)
        {
            $file->song_lyric()->dissociate();
            $file->save();
        }
        else
        {
            $song_lyric_identification = $request->assigned_song_lyrics[0];

            $song_lyric = SongLyric::getByIdOrCreateWithName($song_lyric_identification);

            $file->song_lyric()->associate($song_lyric);
            $file->save();
        }

        $redirect_arr = [
            'save' => route('admin.external.index'),
            'save_edit_song' => isset($song_lyric) ? route('admin.song.edit', $song_lyric) : route('admin.song.index'),
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

        if ($request->has("redirect"))
        {
            return redirect($request->redirect);
        }

        return redirect()->back();
    }
}
