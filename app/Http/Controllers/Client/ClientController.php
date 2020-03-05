<?php

namespace App\Http\Controllers\Client;

use App\Author;
use App\Http\Controllers\Controller;
use App\SongLyric;
use App\External;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Return view with Vue single page application.
     *
     * @return Factory|View
     */
    public function spa()
    {
        return view('client.spa');
    }
}
