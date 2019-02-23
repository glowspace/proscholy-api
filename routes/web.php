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
Route::get('/o-tymu', 'Client\HomeController@renderAboutTeam')->name('client.team');


// Redirects to real search route.
Route::post('/vyhledavani/send_search', 'Client\SearchController@searchSend')->name('client.search');
// The real user search route
// Route::get('/vyhledavani/', 'Client\SearchController@searchResults')->name('client.search_results');
Route::get('/vyhledavani/{phrase?}', 'Client\SearchController@searchResults')->name('client.search_results');

Route::get('/seznam-pisni', 'Client\ListController@renderSongListAlphabetical')->name('client.song.list');
Route::get('/seznam-autoru', 'Client\ListController@renderAuthorListAlphabetical')->name('client.author.list');

// Client single model views
Route::get('/pisen/{song_lyric}/text', 'Client\SongLyricsController@songText')->name('client.song.text');
Route::get('/pisen/{song_lyric}/noty', 'Client\SongLyricsController@songScore')->name('client.song.score');
Route::get('/pisen/{song_lyric}/preklady', 'Client\SongLyricsController@songOtherTranslations')->name('client.song.translations');
Route::get('/pisen/{song_lyric}/nahravky', 'Client\SongLyricsController@songAudioRecords')->name('client.song.audio_records');
Route::get('/pisen/{song_lyric}/videa', 'Client\SongLyricsController@songVideos')->name('client.song.videos');
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
Auth::routes(['register' => false]);
Route::get('/logout', 'Auth\LoginController@logout')->name('auth.logout');

// Downloading
Route::get('/download/{file}/{filename?}', 'DownloadController@downloadFile')->name('download.file');

Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'Admin'], function ()
{
    Route::get('/', 'AdminController@renderDash')->name('admin.dashboard');
    Route::get('/todo', 'AdminController@renderTodo')->name('admin.todo');

    // // External
    Route::get('/externals', 'ExternalController@index')->name('admin.external.index');
    Route::get('/external/new', 'ExternalController@create')->name('admin.external.create');
    Route::get('/external/new-for-song/{song_lyric}', 'ExternalController@create_for_song')->name('admin.external.create_for_song');
    Route::post('/external/new', 'ExternalController@store')->name('admin.external.store');
    Route::get('/external/{external}', 'ExternalController@edit')->name('admin.external.edit');
    Route::put('/external/{external}', 'ExternalController@update')->name('admin.external.update');
    Route::delete('/external/{external}', 'ExternalController@destroy')->name('admin.external.delete');

    // Song
    Route::get('/songs', 'SongController@index')->name('admin.song.index');
    Route::get('/song/new', 'SongController@create')->name('admin.song.create');
    Route::post('/song/new', 'SongController@store')->name('admin.song.store');
    Route::get('/song/{song_lyric}', 'SongController@edit')->name('admin.song.edit');
    Route::put('/song/{song_lyric}', 'SongController@update')->name('admin.song.update');
    Route::delete('/song/{song_lyric}', 'SongController@destroy')->name('admin.song.delete');

    Route::get('/songs/{song_lyric}/refresh-updating', 'SongController@refresh_updating')->name('admin.song.refresh_updating');
    Route::post('/song/resolve-error/{song}', 'SongController@resolve_error')->name('admin.song.resolve_error');

    // Author
    Route::get('/authors', 'AuthorController@index')->name('admin.author.index');
    Route::get('/author/new', 'AuthorController@create')->name('admin.author.create');
    Route::post('/author/new', 'AuthorController@store')->name('admin.author.store');
    Route::get('/author/{author}', 'AuthorController@edit')->name('admin.author.edit');
    Route::put('/author/{author}', 'AuthorController@update')->name('admin.author.update');
    Route::delete('/author/{author}', 'AuthorController@destroy')->name('admin.author.delete');

    // File
    Route::get('/files', 'FileController@index')->name('admin.file.index');
    Route::get('/file/new', 'FileController@create')->name('admin.file.create');
    Route::get('/file/new-for-song/{song_lyric}', 'FileController@create_for_song')->name('admin.file.create_for_song');
    Route::post('/file/new', 'FileController@store')->name('admin.file.store');
    Route::get('/file/{file}', 'FileController@edit')->name('admin.file.edit');
    Route::put('/file/{file}', 'FileController@update')->name('admin.file.update');
    Route::delete('/file/{file}', 'FileController@destroy')->name('admin.file.delete');

    Route::group(['middleware' => ['permission:manage users']], function () {
        Route::get('/users', 'UserController@index')->name('admin.user.index');
        Route::get('/user/new', 'UserController@create')->name('admin.user.create');
        Route::post('/user/new', 'UserController@store')->name('admin.user.store');
        Route::get('/user/{user}', 'UserController@edit')->name('admin.user.edit');
        Route::put('/user/{user}', 'UserController@update')->name('admin.user.update');
        Route::delete('/user/{user}', 'UserController@destroy')->name('admin.user.delete');
    });
});