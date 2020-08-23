<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Songbook;
use Illuminate\Support\Facades\Auth;

class SongbookController extends Controller
{
    public function index()
    {
        return view('admin.form.index', [
            'model_name' => 'songbook',
            'title' => 'Seznam zpěvníků'
        ]);
    }

    public function edit(Request $request, Songbook $songbook)
    {
        return view('admin.form.edit', [
            'model_name' => 'songbook',
            'model_id' => $songbook->id,
            'title' => 'Zpěvník ' . $songbook->name
        ]);
    }
}
