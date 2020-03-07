<?php

/**
 * Special custom redirect routes.
 */
Route::get('/advent', function () {
    return redirect(url('/search?searchString=&tags=24&langs=&songbooks='));
});
Route::get('/vanoce', function () {
    return redirect(url('/search?searchString=&tags=22&langs=&songbooks='));
});
Route::get('/velikonoce', function () {
    return redirect(url('/search?searchString=&tags=23&langs=&songbooks='));
});
Route::get('/postni-doba', function () {
    return redirect(url('/search?searchString=&tags=25&langs=&songbooks='));
});

/**
 * SPA public client routes.
 * Every request except previous ones should go to SPA routes.
 *
 * TODO: Maybe find (prettier) better solution.
 */
Route::get('/', 'Client\ClientController@spa');
Route::get('/{a}', 'Client\ClientController@spa');
Route::get('/{a}/{b}', 'Client\ClientController@spa');
Route::get('/{a}/{b}/{c}', 'Client\ClientController@spa');


/**
 * Client single model views
 * @deprecated
 * Kept only because old import. To be deleted soon.
 */
Route::get('/pisen/{song_lyric}/{name?}', 'Client\SongLyricsController@songText')->name('client.song.text');
