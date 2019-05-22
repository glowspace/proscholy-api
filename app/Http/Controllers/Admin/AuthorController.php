<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Author;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::restricted()->get();
        return view('admin.author.index', compact('authors'));
    }

    public function edit(Author $author)
    {
        // check if user has permission to edit this author
        if (Auth::user()->hasRole('autor') && 
            Auth::user()->assigned_authors()->where('authors.id', $author->id)->count() == 0) {
            return abort(403, "Nepovolený přístup k autorovi $author->name");
        }

        return view('admin.author.edit', compact('author'));
    }

    public function destroy(Request $request, Author $author)
    {
        $author->delete();

        if ($request->has("redirect")) {
            return redirect($request->redirect);
        }

        return redirect()->back();
    }
}
