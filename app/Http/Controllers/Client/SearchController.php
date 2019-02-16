<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ISearchResult;
use App\SongLyric;

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
        if (empty($request['query']))
        {
            return redirect()->back();
        }

        return redirect()->route('client.search_results', $request['query']);
    }

    /**
     * The real search route.
     * Performs search query using Laravel Scaut and pass results into view.
     *
     * @param $phrase
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchResults($phrase)
    {
        $limit = 5;

        $song_lyrics = SongLyric::search($phrase)->limit($limit)->get();
        $authors = Author::search($phrase)->limit($limit)->get();

        return view('client.search_results', [
            'phrase' => $phrase,
            'song_lyrics' => $song_lyrics,
            'authors' => $authors
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
