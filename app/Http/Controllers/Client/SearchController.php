<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search($phrase)
    {
        return view('client.search_results', [
            'phrase' => $phrase,
        ]);
    }
}
