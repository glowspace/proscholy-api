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
    public function index(){
        return view('admin.file.index', ['type' => 'show-all']);
    }

    public function todoAuthors(){
        $type = "show-todo";

        $title = "Seznam souborů bez přiřazeného autora nebo písně";
        return view('admin.file.index', compact('type', 'title'));
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
            'path' => $path,
            'type' => 3 // set default to sheet music
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
        $assigned_authors = $file->authors;
        $all_authors      = Author::select(['id', 'name'])->orderBy('name')->get();

        $assigned_song_lyrics = $file->song_lyric ? [$file->song_lyric] : [];
        $all_song_lyrics      = SongLyric::restricted()->select(['id', 'name'])->orderBy('name')->get();

        return view('admin.file.edit', compact(
            'file',
            'assigned_authors', 'all_authors',
            'assigned_song_lyrics', 'all_song_lyrics'
        ));
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\File  $file
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Request $request, File $file)
    // {
    //     $file->delete();

    //     if ($request->has("redirect"))
    //     {
    //         return redirect($request->redirect);
    //     }

    //     return redirect()->back();
    // }
}
