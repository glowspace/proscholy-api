<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * @param $phrase
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchResults($phrase)
    {
        return view('client.search_results', [
            'phrase' => $phrase,
        ]);
    }
}
