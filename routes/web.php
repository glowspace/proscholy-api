<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Public routes.
 */
Route::get('/', 'Client\HomeController@renderHome')->name('client.home');
Route::get('/o-zpevniku', 'Client\HomeController@renderAboutSongbook')->name('client.about');


// Redirects to real search route.
Route::post('/vyhledavani/send_search', 'Client\SearchController@searchSend')->name('client.search');
// The real user search route
// Route::get('/vyhledavani/', 'Client\SearchController@searchResults')->name('client.search_results');
// Route::get('/vyhledavani/{phrase?}', 'Client\SearchController@searchResults')->name('client.search_results');
Route::get('/search', 'Client\HomeController@renderHome')->name('client.search_results');

Route::get('/seznam-pisni', 'Client\ListController@renderSongListAlphabetical')->name('client.song.list');
Route::get('/seznam-autoru', 'Client\ListController@renderAuthorListAlphabetical')->name('client.author.list');

// Client single model views
// Route::get('/pisen/{song_lyric}/noty', 'Client\SongLyricsController@songScore')->name('client.song.score');
// Route::get('/pisen/{song_lyric}/preklady', 'Client\SongLyricsController@songOtherTranslations')->name('client.song.translations');
// Route::get('/pisen/{song_lyric}/nahravky', 'Client\SongLyricsController@songAudioRecords')->name('client.song.audio_records');
// Route::get('/pisen/{song_lyric}/videa', 'Client\SongLyricsController@songVideos')->name('client.song.videos');
// Route::get('/pisen/{song_lyric}/soubory', 'Client\SongLyricsController@songFiles')->name('client.song.files');
Route::get('/pisen/{song_lyric}/{name?}', 'Client\SongLyricsController@songText')->name('client.song.text');
Route::get('/autor/{author}', 'Client\AuthorController@renderAuthor')->name('client.author');
// TODO: Songbook view
Route::get('/zpevnik/{songbook}', 'Client\SongbookController@renderSongbook')->name('client.songbook');

// Client forms
// TODO: Public content request
Route::get('/navrh/{id}', 'RequestController@request')->name('client.request');
Route::post('/navrh/{id}', 'RequestController@storeRequest')->name('client.request');

// TODO: Report song licence abuse
Route::get('/report', 'Client\ReportController@report')->name('client.report');
Route::post('/report', 'Client\ReportController@storeReport')->name('client.report');

/**
 * Administrace.
 */
Route::get('/logout', 'Auth\LoginController@logout')->name('auth.logout');
Auth::routes(['register' => false]);

// Downloading
Route::get('/download/{file}/{filename?}', 'DownloadController@downloadFileOld')->name('download.file');
Route::get('/preview/{file}/{filename?}', 'DownloadController@downloadFileOld')->name('preview.file');
// todo: create a preview route..?
Route::get('/soubor/{filename}', 'DownloadController@downloadFile')->name('file.download');


// Thumbnails for pdf files
Route::get('/thumbnail/external/{external}', 'DownloadController@getThumbnailExternal')->name('external.thumbnail');
Route::get('/thumbnail/{file}/{filename?}', 'DownloadController@getThumbnailFile')->name('file.thumbnail');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {
    Route::group(['middleware' => 'role:admin|editor|autor', 'namespace' => 'Admin'], function () {
        Route::get('/', 'AdminController@renderDash')->name('dashboard');

        Route::resource('external', 'ExternalController')->only(['index', 'edit']);
        Route::get('/external/new-for-song/{song_lyric}', 'ExternalController@create_for_song')->name('external.create_for_song');

        Route::resource('author', 'AuthorController')->only(['index', 'edit']);
        Route::resource('songbook', 'SongbookController')->only(['index', 'edit']);
        Route::resource('news-item', 'NewsItemController')->only(['index', 'edit']);

        Route::get('/songs', 'SongController@index')->name('song.index');
        Route::get('/song/{song_lyric}/edit', 'SongController@edit')->name('song.edit');

        Route::resource('tag', 'TagController')->except(['show']);

        Route::group(['middleware' => ['permission:manage users']], function () {
            Route::resource('user', 'UserController')->except(['show']);
        });
    });
});

// refreshing
Route::get('/refresh-updating/{type}/{id}', 'Api\LockController@refresh_updating');

Route::get('/ucet', function () {
    return view('client.account');
})->name('client.account');

// routes for propagation
Route::get('/advent', function () {
    return redirect(url('/?stitky=24'));
});
Route::get('/vanoce', function () {
    return redirect(url('/?stitky=22'));
});
Route::get('/velikonoce', function () {
    return redirect(url('/?stitky=23'));
});
Route::get('/postni-doba', function () {
    return redirect(url('/?stitky=25'));
});

Route::get('/reset-cache', function () {
    if (opcache_reset()) {
        return response("OPCache successfully reset\n");
    } else {
        return response("OPCache was ***not*** reset\n");
    }
});


// Route::get('/firebase-auth/me', function(Request $request) {
//     return (array) $request->public_user();
// })->middleware('auth:web_firebase');
