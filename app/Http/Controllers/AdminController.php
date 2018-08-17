<?php

namespace App\Http\Controllers;

use App\Author;
use App\Song;
use App\SongTranslation;
use App\Video;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function renderDash()
    {
        return view('admin.dash');
    }

    /*
     * Songy.
     */

    public function renderNewSong()
    {
        return view('admin.song');
    }

    public function storeNewSong(Request $request)
    {
        $song           = new Song();
        $song->name     = $request['name'];
        $song->visible  = 0;
        $song->approved = 0;
        $song->save();

        $translation                = new SongTranslation();
        $translation->name          = $request['name'];
        $translation->song_id       = $song->id;
        $translation->is_authorized = 1;
        $translation->is_original   = 1;
        $translation->save();

        if ( ! empty($request['translation_name']))
        {
            $translation                = new SongTranslation();
            $translation->name          = $request['translation_name'];
            $translation->song_id       = $song->id;
            $translation->is_authorized = 0;
            $translation->is_original   = 0;
            $translation->save();
        }

        return redirect()->route('admin.song.new');
    }


    /*
     * Video.
     */

    public function renderVideos()
    {
        return view('admin.videos', [
            'videos' => Video::all(),
            'todo'   => Video::all()->where('author_id', '')->concat(Video::all()->where('song_translation_id', '')),
        ]);
    }

    public function renderVideoCreate()
    {
        return view('admin.video_new');
    }

    public function storeVideoCreate(Request $request)
    {
        if ($this->checkIfVideoExists($request['url']))
        {
            return redirect()->route('admin.video.new');
        }

        $video      = new Video();
        $video->url = $request['url'];
        $video->save();

        return redirect()->route('admin.video.new');
    }

    public function renderVideoEdit($id)
    {
        return view('admin.video_edit', [
            'video' => Video::findOrFail($id),
        ]);
    }

    public function storeVideoEdit(Request $request)
    {
        $video      = Video::findOrFail($request['id']);
        $video->url = $request['url'];
        $video->save();

        return redirect()->route('admin.video.edit', ['id' => $video->id]);
    }

    public function renderVideoEditAuthor($id)
    {
        return view('admin.video_author', [
            'video'   => Video::findOrFail($id),
            'authors' => Author::all(),
        ]);
    }


    public function storeVideoEditAuthor($video_id, $author_id)
    {
        $video            = Video::findOrFail($video_id);
        $video->author_id = $author_id;
        $video->save();

        return redirect()->route('admin.video.edit.author', ['id' => $video_id]);
    }

    public function renderVideoEditTranslation($id)
    {
        return view('admin.video_translation', [
            'video'        => Video::findOrFail($id),
            'translations' => SongTranslation::all(),
        ]);
    }

    public function storeVideoEditTranslation($video_id, $translation_id)
    {
        $video                      = Video::findOrFail($video_id);
        $video->song_translation_id = $translation_id;
        $video->save();

        return redirect()->route('admin.video.edit.translation', ['id' => $video_id]);
    }

    private function checkIfVideoExists($url)
    {
        $videos = Video::all()->where('url', $url)->count();

        if ($videos > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
