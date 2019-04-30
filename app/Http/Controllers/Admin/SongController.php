<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Song;
use App\SongLyric;
use App\Author;
use App\Tag;

class SongController extends Controller
{
    private function apply_request_filters($query, Request $request)
    {
        $res_query = $query;
        if ($request->has('filter_author_id')) {
            $res_query = $res_query->whereHas('authors', function($q) use ($request) {
                $q->where('authors.id', $request['filter_author_id']);
            });
        }

        return $res_query;
    }

    public function index(Request $request)
    {
        $query = SongLyric::orderBy('name')->restricted();
        $song_lyrics = $this->apply_request_filters($query, $request)->get();

        return view('admin.song.index', compact('song_lyrics'));
    }

    public function todoLyrics(Request $request)
    {
        $query = SongLyric::where('lyrics', '=', null)->orderBy('name');
        $song_lyrics = $this->apply_request_filters($query, $request)->get();

        $title = "Seznam písní bez textu";
        return view('admin.song.index', compact('song_lyrics', 'title'));
    }

    public function todoAuthors(Request $request) 
    {
        $query = SongLyric::whereDoesntHave('authors')->where('has_anonymous_author', 0)->orderBy('name');
        $song_lyrics = $this->apply_request_filters($query, $request)->get();

        $title = "Seznam písní bez přiřazeného autora";
        return view('admin.song.index', compact('song_lyrics', 'title'));
    }

    public function todoChords(Request $request)
    {
        $query = SongLyric::where('lyrics', '!=', null)->where('has_chords', 0)->orderBy('name');
        $song_lyrics = $this->apply_request_filters($query, $request)->get();

        $title = "Seznam písní s textem bez akordů";
        return view('admin.song.index', compact('song_lyrics', 'title'));
    }

    public function todoTags(Request $request) {
        $query = SongLyric::whereDoesntHave('tags')->orderBy('name');
        $song_lyrics = $this->apply_request_filters($query, $request)->get();

        $title = "Seznam písní bez štítků";
        return view('admin.song.index', compact('song_lyrics', 'title'));
    }

    public function todoPublish(Request $request) {
        $query = SongLyric::where('is_published', 0)->orderBy('name');
        $song_lyrics = $this->apply_request_filters($query, $request)->get();

        $title = "Seznam písní k publikování";
        return view('admin.song.index', compact('song_lyrics', 'title'));
    }

    // author account todo
    public function todoApprove(Request $request)
    {
        $query = SongLyric::restricted()->where('is_approved_by_author', 0)->orderBy('name');
        $song_lyrics = $this->apply_request_filters($query, $request)->get();

        $title = "Seznam písní ke schválení autorem";
        return view('admin.song.index', compact('song_lyrics', 'title'));
    }

    public function create()
    {
        return view('admin.song.create');
    }

    public function store(Request $request)
    {
        $song_l = SongLyric::getByIdOrCreateWithName($request->name);

        $redirect_arr = [
            'edit' => route('admin.song.edit', ['id' => $song_l->id]),
            'create' => route('admin.song.create')
        ];

        return redirect($redirect_arr[$request->redirect]);
    }

    public function edit(SongLyric $song_lyric)
    {
        // check if user has permissions to edit current song
        if (Auth::user()->hasRole('autor') && 
            SongLyric::restricted()->where('id', $song_lyric->id)->count() == 0) {
            return abort(403, "Nepovolený přístup k písni $song_lyric->name");
        }

        if (!$song_lyric->lock()) {
            // unsuccesful attempt to lock -> that means the model has been locked
            return view('admin.song.error', [
                'error' => 'locked',
                'song_lyric' => $song_lyric 
            ]);
        }

        // prepare fields for the (author) magicsuggest component
        $assigned_authors = $song_lyric->authors()->select(['authors.id', 'authors.name'])->get();
        $all_authors = Author::select(['id', 'name'])->orderBy('name')->get();

        $domestic_song_lyric = $song_lyric->song->getDomesticSongLyric($song_lyric->id);
        // prepare a singleton array for the (original song) magicsuggest component
        $assigned_song_lyrics = $domestic_song_lyric ? [$domestic_song_lyric] : [];
        $all_song_lyrics = SongLyric::select(['id', 'name'])->where('id', '!=', $song_lyric->id)->get();

        // do not allow to change the field in this situation
        // (instead show a list of connected Songs)
        // - this means this SongLyric is head of the group of song - aka domestic and 
        //   had been set as original of some other songs
        $assigned_song_disabled = $song_lyric->hasSiblings() && $song_lyric->isDomestic();

        // HANDLING OF TAGS
        $official_tags = Tag::officials()->orderBy('name')->get();
        $assigned_official_tags = $song_lyric->tags()->officials()->get();

        $unofficial_tags = Tag::unofficials()->orderBy('name')->get();
        $assigned_unofficial_tags = $song_lyric->tags()->unofficials()->get();

        // dd($assigned_official_tags);

        // loading file preview
        $score_file = $song_lyric->scoreFiles()->first();
        $score_external = $song_lyric->scoreExternals()->first();

        return view('admin.song.edit', compact(
            'song_lyric', 
            'assigned_authors', 'all_authors',
            'assigned_song_lyrics', 'all_song_lyrics', 'assigned_song_disabled',
            'official_tags', 'assigned_official_tags',
            'unofficial_tags', 'assigned_unofficial_tags',
            'score_file', 'score_external'));
    }

    public function destroy(Request $request, SongLyric $song_lyric)
    {
        // TODO: find if a Song model that had been linked to this SongLyric has no dependencies anymore
        // in the case delete this one as well

        $song_lyric->delete();

        if ($request->has("redirect")) {
            return redirect($request->redirect);
        }

        return redirect()->back();
    }

    public function update(Request $request, SongLyric $song_lyric)
    {
        $firstTimeUpdating = $song_lyric->created_at->eq($song_lyric->updated_at);
        // $firstTimeUpdating = true;

        // name has changed ???
        if ($request->name !== $song_lyric->name) {
            // to be domestic means to have a same name as the parent song
            // this invariant needs to be preserved in order to stay domestic
            if ($song_lyric->isDomestic()) {
                $song_lyric->song->name = $request->name;
                $song_lyric->song->save();
            }
        }
        $song_lyric->update($request->all());

        // need to handle the checkbox
        if (!$request->has("has_anonymous_author")) {
            $song_lyric->has_anonymous_author = 0;
            $song_lyric->save();
        }

        // SYNCING THE AUTHORS

        if ($request->authors !== NULL) {
            // old authors that had been saved in db - an ID is passed
            $saved_authors = Arr::where($request->authors, function ($value, $key) {
                return is_numeric($value);
            });
            $song_lyric->authors()->sync($saved_authors);
    
            // new authors to create - a string NAME is passed
            $new_authors = Arr::where($request->authors, function ($value, $key) {
                return !is_numeric($value);
            });
    
            // create new authors and associate to current song_lyric model
            foreach ($new_authors as $author) {
                $song_lyric->authors()->create(['name' => $author]);
            }
            $song_lyric->save();
        } else {
            $song_lyric->authors()->sync([]);
            $song_lyric->save();
        }

        // SYNCING AND HANDLING THE ASSOCIATED SONGS

        if ($request->assigned_song_lyrics === NULL && $song_lyric->isCuckoo()) {
            // this means I was a cuckoo so I need to find a new parent,
            // that is gonna have a same name as me, so I'm not gonna be a cockoo anymore :P
            $new_parent = Song::create(['name' => $song_lyric->name]);
            $song_lyric->song()->associate($new_parent);
            $song_lyric->save();
        }

        if ($request->assigned_song_lyrics !== NULL && ($song_lyric->isCuckoo() || $song_lyric->isDomesticOrphan())) {
            $identificator = $request->assigned_song_lyrics[0];

            $friend = SongLyric::getByIdOrCreateWithName($identificator);

            // this song is supposed to be an original if we want that explicitly
            if ($request->set_linked_dong == "set_original") {
                $friend->is_original = 1;
            // this song is supposed to be a translation if we want that explicitly
            } else if ($request->set_linked_dong == "set_translation") {
                $friend->is_original = 0;
            }
            $friend->save();

            // associate to the friends Song and stay/become a Cuckoo :) :O
            $song_lyric->song()->associate($friend->song);
            $song_lyric->save();

            // hold some kind of structure
            if ($request->is_original == 1) {
                $song_lyric->song->name = $song_lyric->name;
                $song_lyric->song->save();
            }
        }

        // SYNCING THE TAGS
        $all_tags = [];

        foreach (array_merge(
            $request->unofficial_tags ?? [], 
            $request->official_tags ?? []) as $identificator)
        {
            $all_tags[] = Tag::getByIdOrCreateWithName($identificator)->id;
        }

        $song_lyric->tags()->sync($all_tags);

        // UNLOCKING FOR EDIT
        $song_lyric->unlock();

        // CHECKING FOR CONSISTENCY

        // 1. case: there is a group of SongLyrics under one Song that have no original 
        // do this only when updating the model for the first time - after creating a new model
        if ($song_lyric->hasSiblings() &&
            $song_lyric->song->getOriginalSongLyric() === NULL &&
            $firstTimeUpdating)
        {
            return view('admin.song.error', [
                'error' => 'no_original',
                'song' => $song_lyric->song 
            ]);
        }
            
        // 2. case: there is more originals in one group
        if ($song_lyric->song->song_lyrics()->where('is_original', 1)->count() > 1)
        {
            return view('admin.song.error', [
                'error' => 'more_originals',
                'song' => $song_lyric->song 
            ]);
        }

        // if required then make the song published
        if ($request->redirect == "save_publish") {
            $song_lyric->update([
                'is_published' => 1
            ]);
        }

        // if required then make the song approved
        if ($request->redirect == "save_approve") {
            $song_lyric->update([
                'is_approved_by_author' => 1
            ]);
        }

        // no error => contunue with redirecting according to a selected action
        $redirect_arr = [
            'save' => route('admin.song.index'),
            'add_external' => route('admin.external.create_for_song', $song_lyric),
            'add_file' => route('admin.file.create_for_song', $song_lyric),
            'save_show' => $song_lyric->public_url,
            'save_publish' => route('admin.song.to-publish'),
            'save_approve' => route('admin.song.to-approve')
        ];

        return redirect($redirect_arr[$request->redirect]);
    }

    public function refresh_updating(SongLyric $song_lyric)
    {
        $song_lyric->lock(true);
        return response('OK', 200);
    }

    public function resolve_error(Request $request, Song $song)
    {
        // select chosen SongLyric as orignal and the rest as translations
        if ($request->solution === 'choose_original') {
            $id_orig = $request->song_original;

            foreach ($song->song_lyrics as $song_l) {
                // $shouldBeOriginal = $song_l->id == $id_orig;
                if ($song_l->id == $id_orig) {
                    $song_l->is_original = true;
                    $song_l->song->name = $song_l->name;
                    $song_l->song->save();
                } else {
                    $song_l->is_original = false;
                }
                $song_l->save();
            }

            return redirect()->route('admin.song.index');
        }
        // create a new original song that'll be the new original in the group
        else if ($request->solution === 'create_original') {
            $song_lyric = SongLyric::create([
                'name' => '',
                'song_id' => $song->id,
                'is_original' => 1
            ]);
            // change name of the parent Song model to be the same as the new SongLyric's name
            // in order to make the new SongLyric domestic => "head" of the group
            $song->name = '';
            $song->save();

            return redirect()->route('admin.song.edit', $song_lyric);
        }
        // don't do anything
        else if ($request->solution === 'keep') {
            return redirect()->route('admin.song.index');
        }
    }
}
