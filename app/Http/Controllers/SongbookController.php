<?php

namespace App\Http\Controllers;

use App\Songbook;
use App\SongbookRecord;

class SongbookController extends Controller
{
    public function renderSongbook($id)
    {
        return view('songbook', [
            'songbook' => Songbook::findOrFail($id),
            'records'  => SongbookRecord::where('songbook_id', $id)->get(),
        ]);
    }
}
