<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Songbook;
use App\SongLyric;

class LockController extends Controller
{
    public function refresh_updating_song_lyric(SongLyric $song_lyric)
    {
        \Log::info($song_lyric);
        $song_lyric->lock(true);
        return response('OK', 200);
    }

    public function refresh_updating_songbook(Songbook $songbook)
    {
        $songbook->lock(true);
        return response('OK', 200);
    }
}
