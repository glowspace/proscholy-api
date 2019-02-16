<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Author;

class AuthorController extends Controller
{
    public function __construct()
    {

    }

    public function index(){
        $authors = Author::all();
        return view('admin.author.index', compact('authors'));
    }

    public function create(){
        return view('admin.author.create');
    }

    public function store(Request $request){
        // TODO: make this line working
        // Authors::create($request->all());

        $author       = new Author();
        $author->name = $request['name'];
        $author->save();

        // if ($request["redirect"] == "edit") {
        //     return redirect()->route('admin.author.edit', ['id' => $author_l->id]);
        // }

        return redirect()->route('admin.author.create');
    }

    public function edit(Author $author)
    {
        return view('admin.author.edit', compact('author'));
    }

    public function destroy(Author $author){
        // TODO: find if a Author model that had been linked to this Author has no dependencies anymore
        // in the case delete this one as well

        $author->delete();
    }

    public function update(Request $request, Author $author)
    {
        $author->update($request->all());
        return redirect()->route('admin.author.index');
    }
}
