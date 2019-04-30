<?php

namespace App\Http\Controllers\Client;

use App\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ISearchResult;
use App\SongLyric;
use App\Author;

class SearchController extends Controller
{
    /**
     * Redirects user to a real search route,
     * (receives user search from over POST method).
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function searchSend(Request $request)
    {
        return redirect()->route('client.search_results', $request['query']);
    }

    /**
     * The real search route.
     * Performs search query using Laravel Scaut and pass results into view.
     *
     * @param $query
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchResults($query = null)
    {
        $limit = 10;
//        $limit_empty;


        if (isset($query))
        {
            $song_lyrics = SongLyric::search($query)->paginate($limit);
            $authors     = Author::search($query)->paginate($limit);
        }
        else
        {
            // Empty search
            // TODO: Let the user know in the frontend, what I'm doing
            $song_lyrics = SongLyric::notEmpty()->orderBy('name')->get();
            $authors     = [];
            $query       = "";
        }

        return view('client.search_results', [
            'phrase'      => $query,
            'song_lyrics' => $song_lyrics,
            'authors'     => $authors,
            'tags'        => Tag::all()->sortByDesc('type')->sortBy('name'),
        ]);
    }

    public function searchResultsOnlySongs($phrase)
    {
        // TODO 
    }

    public function searchResultsOnlyAuthors($phrase)
    {
        // TODO 
    }
}
