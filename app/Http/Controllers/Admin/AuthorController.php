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
        $authors = Author::paginate(100);
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

        if ($request["redirect"] == "edit") {
            return redirect()->route('admin.author.edit', ['author' => $author->id]);
        }

        return redirect()->route('admin.author.create');
    }

    public function edit(Author $author)
    {
        return view('admin.author.edit', compact('author'));
    }

    public function destroy(Author $author){
        
        $author->delete();

        return redirect()->back();
    }

    public function update(Request $request, Author $author)
    {
        $author->update($request->all());
        return redirect()->route('admin.author.index');
    }
}
