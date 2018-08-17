<?php

namespace App\Http\Controllers;

use App\Author;

class AuthorController extends Controller
{
    public function renderAuthor($id)
    {
        $author         = Author::findOrFail($id);
        $author->visits = $author->visits + 1;
        $author->save();

        return view('author', [
            'author' => $author,
            'page_title'  => $author->name,
        ]);
    }
}
