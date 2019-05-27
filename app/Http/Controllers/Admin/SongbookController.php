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
        // $songbooks = Songbook::>get();
        return view('admin.songbook.index');
    }

    public function edit(Songbook $songbook)
    {
        return view('admin.songbook.edit', compact('songbook'));
    }
}
