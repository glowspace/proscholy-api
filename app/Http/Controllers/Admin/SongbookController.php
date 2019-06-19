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
        if ($songbook->isLocked()) {
            return view('admin.lockerror', compact('songbook'));
        }

        $songbook->lock();
        return view('admin.songbook.edit', compact('songbook'));
    }
}
