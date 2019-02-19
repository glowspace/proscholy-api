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
        $song_l->visits += 1;
        $song_l->save();

        foreach ($song_l->authors as $author)
        {
            $author->visits += 1;
            $author->save();
        }

        return view('client.song.song_text', compact('song_l'));
    }

    public function songScore(SongLyric $song_lyric)
    {
        $song_lyric->visits += 1;
        $song_lyric->save();

        return view('client.song.song_scores', ['song_l'=>$song_lyric]);
    }

    public function songOtherTranslations($id)
    {
        $song_l         = SongLyric::findOrFail($id);
        $song_l->visits += 1;
        $song_l->save();

        return view('client.song.song_translations', compact('song_l'));
    }

    public function songAudioRecords($id)
    {
        $song_l         = SongLyric::findOrFail($id);
        $song_l->visits += 1;
        $song_l->save();

        return view('client.song.song_audio_records', compact('song_l'));
    }

    public function songVideos($id)
    {
        $song_l         = SongLyric::findOrFail($id);
        $song_l->visits += 1;
        $song_l->save();

        return view('client.song.song_videos', compact('song_l'));
    }
}
