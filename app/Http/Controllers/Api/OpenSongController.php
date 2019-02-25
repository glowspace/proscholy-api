<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\OpenSongParser;

class OpenSongController extends Controller
{
    public function parseOpenSong(Request $request)
    {
        $helper = new OpenSongParser($request->file_contents);

        return response($helper->getLyrics(), 200);

        // return view('testing.parsing', [
        //     'text' => $helper->getLyrics()
        // ]);
    }
}
