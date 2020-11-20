<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;

class TagController extends Controller
{
    public function index()
    {
        return view('admin.form.index', [
            'model_name' => 'tag',
            'title' => 'Seznam štítků'
        ]);
    }

    public function edit(Request $request, Tag $tag)
    {
        return view('admin.form.edit', [
            'model_name' => 'tag',
            'model_id' => $tag->id,
            'title' => 'Štítek ' . $tag->name
        ]);
    }
}
