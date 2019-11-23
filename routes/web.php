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
Route::get('/download/{file}/{filename?}', 'DownloadController@downloadFile')->name('download.file');
Route::get('/preview/{file}/{filename?}', 'DownloadController@previewFile')->name('preview.file');
// Thumbnails for pdf files
Route::get('/thumbnail/external/{external}', 'DownloadController@getThumbnailExternal')->name('external.thumbnail');
Route::get('/thumbnail/{file}/{filename?}', 'DownloadController@getThumbnailFile')->name('file.thumbnail');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function() {
    Route::group(['middleware' => 'role:admin|editor|autor', 'namespace' => 'Admin'], function () 
    {
        Route::get('/', 'AdminController@renderDash')->name('dashboard');

        Route::resource('external', 'ExternalController')->except(['show', 'update', 'store', 'create']);
        

        Route::get('/external/new-for-song/{song_lyric}', 'ExternalController@create_for_song')->name('external.create_for_song');
        // todo
        Route::get('/externals/no-author', 'ExternalController@todoAuthors')->name('external.no-author');

        Route::get('/songs', 'SongController@index')->name('song.index');
        Route::get('/song/{song_lyric}/edit', 'SongController@edit')->name('song.edit');
        // todo
        Route::get('/songs/no-author', 'SongController@todoAuthors')->name('song.no-author');
        Route::get('/songs/no-lyric', 'SongController@todoLyrics')->name('song.no-lyric');
        Route::get('/songs/no-chord', 'SongController@todoChords')->name('song.no-chord');
        Route::get('/songs/no-tag', 'SongController@todoTags')->name('song.no-tag');
        Route::get('/songs/to-publish', 'SongController@todoPublish')->name('song.to-publish');
        Route::get('/songs/to-approve', 'SongController@todoApprove')->name('song.to-approve');

        Route::resource('author', 'AuthorController')->except(['show', 'update', 'store', 'create']);

        Route::resource('file', 'FileController')->except(['show']);
        Route::get('/file/new-for-song/{song_lyric}', 'FileController@create_for_song')->name('file.create_for_song');
        // todo
        Route::get('/files/no-author', 'FileController@todoAuthors')->name('file.no-author');

        Route::resource('tag', 'TagController')->except(['show']);

        Route::resource('songbook', 'SongbookController')->except(['show', 'update', 'store', 'create']);

        Route::group(['middleware' => ['permission:manage users']], function () {
            Route::resource('user', 'UserController')->except(['show']);
        });
    });
});

// refreshing
Route::get('/refresh-updating/song-lyric/{song_lyric}', 'Api\LockController@refresh_updating_song_lyric');
Route::get('/refresh-updating/songbook/{songbook}', 'Api\LockController@refresh_updating_songbook');

// routes for propagation
Route::get('/advent', function() { return redirect(url('/search?searchString=&tags=24&langs=&songbooks=')); });
Route::get('/vanoce', function() { return redirect(url('/search?searchString=&tags=22&langs=&songbooks=')); });
Route::get('/velikonoce', function() { return redirect(url('/search?searchString=&tags=23&langs=&songbooks=')); });
Route::get('/postni-doba', function() { return redirect(url('/search?searchString=&tags=25&langs=&songbooks=')); });