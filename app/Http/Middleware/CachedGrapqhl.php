<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CachedGrapqhl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */ 
    public function handle($request, Closure $next)
    {
        if ($request->get("operationName") == "FetchSongLyrics"
            && $request->get("variables")["search_str"] == null) {

            $result = Cache::remember('cached_song_lyrics', 10, function () use ($next, $request) {
                return $next($request);
            });

            return $result;
        } else {
            return $next($request);
        }
    }
}
