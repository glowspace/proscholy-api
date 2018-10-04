<?php

namespace App\Http\Controllers;

use App\Author;
use App\Song;
use App\SongbookRecord;
use App\SongLyric;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    public function renderDash()
    {
        return view('admin.dash');
    }

    /*
     * To-do.
     */
    public function renderTodo()
    {
        return view('admin.todo', [
            'videos'                        => Video::where('author_id', '')->orWhere('song_lyric_id', '')->get()->shuffle(),
            // DANGEROUS TYPE MIXING
            'songs_w_author'                => Song::whereDoesntHave('authors')->get()->concat(SongLyric::whereDoesntHave
            ('authors')->get()->where('is_original', '0'))->shuffle(),
            'songbook_record_w_translation' => SongbookRecord::where('song_lyric_id', '')->get(),
            'song_lyrics_w_lyrics'          => SongLyric::where('lyrics', '')->get(),
        ]);
    }

    public function renderTodoRandom()
    {
        $videos_w_author = Video::where('author_id', '')->orWhere('song_lyric_id', '')->get();
        $songs_w_author  = Song::whereDoesntHave('authors')->get()->concat(SongLyric::whereDoesntHave
        ('authors')->get()->where('is_original', '0'))->shuffle();

        $songbook_record_w_translation = SongbookRecord::where('song_lyric_id', '')->get();
        $song_lyrics_w_lyrics          = SongLyric::where('lyrics', '')->get()->shuffle();

        if (
            $videos_w_author->count() == 0 && $songs_w_author->count() == 0 && $songbook_record_w_translation->count() == 0
            && $song_lyrics_w_lyrics->count() == 0
        )
        {
            return redirect()->route('admin.todo');
        }


        $rand = rand(1, 3);

        // Songy bez autora
        if ($rand == 1)
        {
            if ($songs_w_author->count() == 0)
            {
                return redirect()->route('admin.todo.random');
            }

            return view('admin.todo_editors.song_w_author', [
                'song'    => $songs_w_author->first(),
                'authors' => Author::all(),
            ]);
        }
        elseif ($rand == 2)
        {
            if ($songbook_record_w_translation->count() == 0)
            {
                return redirect()->route('admin.todo.random');
            }

            return view('admin.todo_editors.songbook_record_w_translation', [
                'record'       => $songbook_record_w_translation->first(),
                'translations' => SongLyric::all(),
            ]);
        }
        elseif ($rand == 4)
        {
            // Pokud už žádný není - hurá, vyhledáme něco jinýho.
            if ($song_lyrics_w_lyrics->count() == 0)
            {
                return redirect()->route('admin.todo.random');
            }

            return view('admin.todo_editors.translation_w_lyrics', ['translation' => $song_lyrics_w_lyrics->first()]);
        }
        else
        {
            if ($videos_w_author->count() == 0)
            {
                return redirect()->route('admin.todo.random');
            }

            return view('admin.todo_editors.video', ['video' => $videos_w_author->first()]);
        }
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

        $lyric                = new SongLyric();
        $lyric->name          = $request['name'];
        $lyric->song_id       = $song->id;
        $lyric->is_authorized = 1;
        $lyric->is_original   = 1;
        $lyric->save();

        if ( ! empty($request['lyric_name']))
        {
            $lyric                = new SongLyric();
            $lyric->name          = $request['lyric_name'];
            $lyric->song_id       = $song->id;
            $lyric->is_authorized = 0;
            $lyric->is_original   = 0;
            $lyric->save();
        }

        return redirect()->route('admin.song.new');
    }

    /**
     * To-do přidání autora.
     *
     * @param $author_id
     * @param $song_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setSongAuthor($author_id, $song_id)
    {
        $author_name = Input::get('new_author');

        if (isset($author_name))
        {
            $author         = new Author();
            $author->name   = Input::get('new_author');
            $author->visits = 0;
            $author->type   = 0;
            $author->save();

            $song = Song::findOrFail($song_id);
            $song->authors()->attach([$author->id]);
        }
        else
        {
            $song = Song::findOrFail($song_id);
            $song->authors()->attach([$author_id]);
        }

        return redirect()->route('admin.todo.random');
    }

    public function setTranslationAuthor($author_id, $translation_id)
    {
        $author_name = Input::get('new_author');

        if ( ! empty($author_name))
        {
            $author         = new Author();
            $author->name   = Input::get('new_author');
            $author->visits = 0;
            $author->type   = 0;
            $author->save();

            $lyric = SongLyric::findOrFail($lyric_id);
            $lyric->authors()->attach([$author->id]);
        }
        else
        {
            $lyric = SongLyric::findOrFail($lyric_id);
            $lyric->authors()->attach([$author_id]);
        }

        return redirect()->route('admin.todo.random');
    }

    public function setSongbookRecordLyric($record_id, $lyric_id)
    {
        $record                = SongbookRecord::findOrFail($record_id);
        $record->song_lyric_id = $translation_id;
        $record->save();

        return redirect()->route('admin.todo.random');
    }

    /*
     * Video.
     */

    public function renderVideos()
    {
        return view('admin.videos', [
            'videos' => Video::all(),
            'todo'   => Video::where('author_id', '')->orWhere('song_lyric_id', '')->get(),
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
            'translations' => SongLyric::all(),
        ]);
    }

    public function storeVideoEditTranslation($video_id, $translation_id)
    {
        $video                = Video::findOrFail($video_id);
        $video->song_lyric_id = $translation_id;
        $video->save();

        return redirect()->route('admin.video.edit.translation', ['id' => $video_id]);
    }

    private function checkIfVideoExists($url)
    {
        $videos = Video::where('url', $url)->count();

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
