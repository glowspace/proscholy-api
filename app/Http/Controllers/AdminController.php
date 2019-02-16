<?php

namespace App\Http\Controllers;

use App\Author;
use App\Song;
use App\SongbookRecord;
use App\SongLyric;
use App\External;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
    public function renderDash()
    {
        return view('admin.dash');
    }

    /**
     * Stránka pro doplňování obsahu.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderTodo()
    {
        return view('admin.todo', [
            'externals'                        => External::where('author_id', null)->orWhere('song_lyric_id', null)->get(),
            'songs_w_author'                => SongLyric::whereDoesntHave('authors')->get(),
            'songbook_record_w_translation' => SongbookRecord::where('song_lyric_id', '')->get(),
            'song_lyrics_w_lyrics'          => SongLyric::where('lyrics', '=', null)->get(),
        ]);
    }

    public function renderTodoRandom()
    {
        $externals_w_author = External::where('author_id', '')->orWhere('song_lyric_id', '')->get();
        $songs_w_author  = Song::whereDoesntHave('authors')->get()->concat(SongLyric::whereDoesntHave
        ('authors')->get()->where('is_original', '0'))->shuffle();

        $songbook_record_w_translation = SongbookRecord::where('song_lyric_id', '')->get();
        $song_lyrics_w_lyrics          = SongLyric::where('lyrics', '')->get()->shuffle();

        if (
            $externals_w_author->count() == 0 && $songs_w_author->count() == 0 && $songbook_record_w_translation->count() == 0
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
            if ($externals_w_author->count() == 0)
            {
                return redirect()->route('admin.todo.random');
            }

            return view('admin.todo_editors.external', ['external' => $externals_w_author->first()]);
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
        $song       = new Song();
        $song->name = $request['name'];
        $song->save();

        $lyric                = new SongLyric();
        $lyric->name          = $request['name'];
        $lyric->song_id       = $song->id;
        $lyric->is_authorized = 1;
        $lyric->is_original   = 1;
        $lyric->lang          = 'cs';
        $lyric->saveOrFail();

        if ( ! empty($request['translation_name']))
        {
            $translation_lyric                = new SongLyric();
            $translation_lyric->name          = $request['translation_name'];
            $translation_lyric->song_id       = $song->id;
            $translation_lyric->is_authorized = 0;
            $translation_lyric->is_original   = 0;
            $translation_lyric->lang          = 'cs';
            $translation_lyric->saveOrFail();
        }

        return redirect()->route('admin.song.new');
    }

    public function renderSongs()
    {
        return view('admin.songs', [
            'songs' => SongLyric::all(),
        ]);
    }

    public function renderSongEdit($id)
    {
        return view('admin.song', [
            'song' => SongLyric::findOrFail($id),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function storeSongEdit(Request $request)
    {
        $song = SongLyric::findOrFail($request['id']);

        $data = $request->except(['id', '_token']);

        $song->fill($data);

        $song->saveOrFail();

        return redirect()->route('admin.todo');
    }

    public function renderAddSongAuthor($song_id)
    {
        return view('admin.todo_editors.song_w_author', [
            'song'    => SongLyric::findOrFail($song_id),
            'authors' => Author::all(),
        ]);
    }

    /**
     * @param $author_id
     * @param $lyric_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setSongAuthor($author_id, $lyric_id)
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

        return redirect()->route('admin.todo');
    }

    /*
     * External.
     */

    public function renderExternals()
    {
        return view('admin.externals', [
            'externals' => External::all(),
            'todo'   => External::where('author_id', '')->orWhere('song_lyric_id', '')->get(),
        ]);
    }

    public function renderExternalCreate()
    {
        return view('admin.external_new');
    }

    public function storeExternalCreate(Request $request)
    {
        if ($this->checkIfExternalExists($request['url']))
        {
            return redirect()->route('admin.external.new');
        }

        $external         = new External();
        $external->url    = $request['url'];
        $external->type   = 0; // 0 = YT
        $external->visits = 0;
        $external->save();

        return redirect()->route('admin.external.new');
    }

    public function renderExternalEdit($id)
    {
        return view('admin.external_edit', [
            'external' => External::findOrFail($id),
        ]);
    }

    public function storeExternalEdit(Request $request)
    {
        $external      = External::findOrFail($request['id']);
        $external->url = $request['url'];
        $external->save();

        return redirect()->route('admin.external.edit', ['id' => $external->id]);
    }

    public function renderExternalEditAuthor($id)
    {
        return view('admin.external_author', [
            'external'   => External::findOrFail($id),
            'authors' => Author::all(),
        ]);
    }


    public function storeExternalEditAuthor($external_id, $author_id)
    {
        $external            = External::findOrFail($external_id);
        $external->author_id = $author_id;
        $external->save();

        return redirect()->route('admin.external.edit.author', ['id' => $external_id]);
    }

    public function renderExternalEditTranslation($id)
    {
        return view('admin.external_translation', [
            'external'        => External::findOrFail($id),
            'translations' => SongLyric::all(),
        ]);
    }

    public function storeExternalEditTranslation($external_id, $translation_id)
    {
        $external                = External::findOrFail($external_id);
        $external->song_lyric_id = $translation_id;
        $external->save();

        return redirect()->route('admin.external.edit.translation', ['id' => $external_id]);
    }

    private function checkIfExternalExists($url)
    {
        $externals = External::where('url', $url)->count();

        if ($externals > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function renderNewAuthor()
    {
        return view('admin.author');
    }

    public function storeNewAuthor(Request $request)
    {
        $author         = new Author();
        $author->name   = $request['name'];
        $author->type   = $request['type'];
        $author->visits = 0;
        $author->save();

        return redirect()->route('admin.author.new');
    }
}
