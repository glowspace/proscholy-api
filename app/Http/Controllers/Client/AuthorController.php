<?php

namespace App\Http\Controllers\Client;

use App\Author;
use App\Http\Controllers\Controller;

class AuthorController extends Controller
{
    public function renderAuthor($id)
    {
        $author         = Author::findOrFail($id);
        $author->visits = $author->visits + 1;
        $author->save();

        $originals = $author->songLyricsWithAssociatedAuthors()->originals()->orderBy('name')->get();
        $translations = $author->songLyricsWithAssociatedAuthors()->translations()->orderBy('name')->get();

        $interpreted = $author->getSongLyricsInterpreted()->orderBy('name')->get()->diff($originals->merge($translations));

        return view('client.author', [
            'author'     => $author,
            'page_title' => $author->name,
            'originals' => $originals,
            'translations' => $translations,
            'interpreted' => $interpreted
        ]);
    }
}
