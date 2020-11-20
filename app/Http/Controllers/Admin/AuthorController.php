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
        return view('admin.form.index', [
            'model_name' => 'author',
            'title' => 'Seznam autorů'
        ]);
    }

    public function edit(Request $request, Author $author)
    {
        return view('admin.form.edit', [
            'model_name' => 'author',
            'model_id' => $author->id,
            'title' => 'Autor ' . $author->name
        ]);
    }
}
