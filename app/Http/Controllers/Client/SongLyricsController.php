<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Song;
use App\SongLyric;
use Illuminate\Http\Request;

class SongLyricsController extends Controller
{
    public function songText($id)
    {
        $song_l         = SongLyric::findOrFail($id);
        // $song_l->visits += 1;
        // $song_l->save();

        // foreach ($song_l->authors as $author)
        // {
        //     $author->visits += 1;
        //     $author->save();
        // }

        // $reversed_columns = $song_l->lyrics == "" && 
        //                     $song_l->scoreFiles()->count() + $song_l->scoreExternals()->count() > 0;

        // if ($song_l->lyrics == "" && $song_l->scoreFiles()->count() > 0) {
        //     return view('client.song.song_scores', compact('song_l'));
        // }

        $songbook_records = $song_l->songbook_records()->get();

        $tags_officials = $song_l->tags()->officials()->orderBy('name')->get();
        $tags_unofficials = $song_l->tags()->unofficials()->orderBy('name')->get();

        return view('client.song', compact('song_l', 'tags_officials', 'tags_unofficials', 'songbook_records'));
    }

    private function getDescription($song_l)
    {
        // TODO: finish this function and implement instead of blade component
        $authors_count = $song_l->authors()->count();
        $original_lyric = $song_l->song->getOriginalSongLyric();
        if($original_lyric !== NULL) {
            $original_authors_count = $original_lyric->authors()->count();
        }

        $desc = "Píseň $song_l->name,";

        if ($song_l->type === 0) {
            if ($authors_count === 0) {
                $desc.= " autor neznámý";
                return $desc;
            }

            if ($authors_count === 1) {
                $desc.= " autor: ";
            } else {
                $desc.= " autoři: ";
            }
        }
    }
}
