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
    public function create()
    {
        return view('admin.file.create');
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
        $filename = $request->file('filename')->getClientOriginalName();

        $file = File::create([
            'filename' => $filename,
            'path' => $path
        ]);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }
}
