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
Route::get('/pisen/{song_lyric}/noty', 'Client\SongLyricsController@songScore')->name('client.song.score');
Route::get('/pisen/{song_lyric}/preklady', 'Client\SongLyricsController@songOtherTranslations')->name('client.song.translations');
Route::get('/pisen/{song_lyric}/nahravky', 'Client\SongLyricsController@songAudioRecords')->name('client.song.audio_records');
Route::get('/pisen/{song_lyric}/videa', 'Client\SongLyricsController@songVideos')->name('client.song.videos');
Route::get('/pisen/{song_lyric}/soubory', 'Client\SongLyricsController@songFiles')->name('client.song.files');
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
// Thumbnails for pdf files
Route::get('/thumbnail/{file}/{filename?}', 'DownloadController@getThumbnailFile')->name('file.thumbnail');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function() {
    Route::group(['middleware' => 'role:admin|editor|autor', 'namespace' => 'Admin'], function () 
    {
        Route::get('/', 'AdminController@renderDash')->name('dashboard');

        Route::resource('external', 'ExternalController')->except(['show']);
        Route::get('/external/new-for-song/{song_lyric}', 'ExternalController@create_for_song')->name('external.create_for_song');
        // todo
        Route::get('/externals/no-author', 'ExternalController@todoAuthors')->name('external.no-author');

        Route::get('/songs', 'SongController@index')->name('song.index');
        Route::get('/song/new', 'SongController@create')->name('song.create');
        Route::post('/song/new', 'SongController@store')->name('song.store');
        Route::get('/song/{song_lyric}', 'SongController@edit')->name('song.edit');
        Route::put('/song/{song_lyric}', 'SongController@update')->name('song.update');
        Route::delete('/song/{song_lyric}', 'SongController@destroy')->name('song.destroy');
        // todo
        Route::get('/songs/no-author', 'SongController@todoAuthors')->name('song.no-author');
        Route::get('/songs/no-lyric', 'SongController@todoLyrics')->name('song.no-lyric');
        Route::get('/songs/no-chord', 'SongController@todoChords')->name('song.no-chord');
        Route::get('/songs/no-tag', 'SongController@todoTags')->name('song.no-tag');
        Route::get('/songs/to-publish', 'SongController@todoPublish')->name('song.to-publish');
        Route::get('/songs/to-approve', 'SongController@todoApprove')->name('song.to-approve');
        // refreshing
        Route::get('/songs/{song_lyric}/refresh-updating', 'SongController@refresh_updating')->name('song.refresh_updating');
        // error
        Route::post('/song/resolve-error/{song}', 'SongController@resolve_error')->name('song.resolve_error');

        Route::resource('author', 'AuthorController')->except(['show']);

        Route::resource('file', 'FileController')->except(['show']);
        Route::get('/file/new-for-song/{song_lyric}', 'FileController@create_for_song')->name('file.create_for_song');
        // todo
        Route::get('/files/no-author', 'FileController@todoAuthors')->name('file.no-author');

        Route::resource('tag', 'TagController')->except(['show']);

        Route::group(['middleware' => ['permission:manage users']], function () {
            Route::resource('user', 'UserController')->except(['show']);
        });
    });
});

// // admin routing for admins and editors
// Route::name('admin.')->group(['prefix' => 'admin', 'middleware' => 'role:admin|editor', 'namespace' => 'Admin'], function () {
//     Route::get('/', 'AdminController@renderDash')->name('admin.dashboard');
    
//     // // External
//     Route::get('/externals', 'ExternalController@index')->name('external.index');
//     Route::get('/external/new', 'ExternalController@create')->name('external.create');
//     Route::get('/external/new-for-song/{song_lyric}', 'ExternalController@create_for_song')->name('external.create_for_song');
//     Route::post('/external/new', 'ExternalController@store')->name('external.store');
//     Route::get('/external/{external}', 'ExternalController@edit')->name('external.edit');
//     Route::put('/external/{external}', 'ExternalController@update')->name('external.update');
//     Route::delete('/external/{external}', 'ExternalController@destroy')->name('external.destroy');
//     // todo
//     Route::get('/externals/no-author', 'ExternalController@todoAuthors')->name('external.no-author');
    
//     // Song
//     Route::get('/songs', 'SongController@index')->name('song.index');
//     Route::get('/song/new', 'SongController@create')->name('song.create');
//     Route::post('/song/new', 'SongController@store')->name('song.store');
//     Route::get('/song/{song_lyric}', 'SongController@edit')->name('song.edit');
//     Route::put('/song/{song_lyric}', 'SongController@update')->name('song.update');
//     Route::delete('/song/{song_lyric}', 'SongController@destroy')->name('song.destroy');
//     // todo
//     Route::get('/songs/no-author', 'SongController@todoAuthors')->name('song.no-author');
//     Route::get('/songs/no-lyric', 'SongController@todoLyrics')->name('song.no-lyric');
//     Route::get('/songs/no-chord', 'SongController@todoChords')->name('song.no-chord');
//     Route::get('/songs/no-tag', 'SongController@todoTags')->name('song.no-tag');
    
//     Route::get('/songs/{song_lyric}/refresh-updating', 'SongController@refresh_updating')->name('song.refresh_updating');
//     Route::post('/song/resolve-error/{song}', 'SongController@resolve_error')->name('song.resolve_error');
    
//     // Author
//     Route::get('/authors', 'AuthorController@index')->name('author.index');
//     Route::get('/author/new', 'AuthorController@create')->name('author.create');
//     Route::post('/author/new', 'AuthorController@store')->name('author.store');
//     Route::get('/author/{author}', 'AuthorController@edit')->name('author.edit');
//     Route::put('/author/{author}', 'AuthorController@update')->name('author.update');
//     Route::delete('/author/{author}', 'AuthorController@destroy')->name('author.destroy');
    
//     // File
//     Route::get('/files', 'FileController@index')->name('file.index');
//     Route::get('/file/new', 'FileController@create')->name('file.create');
//     Route::get('/file/new-for-song/{song_lyric}', 'FileController@create_for_song')->name('file.create_for_song');
//     Route::post('/file/new', 'FileController@store')->name('file.store');
//     Route::get('/file/{file}', 'FileController@edit')->name('file.edit');
//     Route::put('/file/{file}', 'FileController@update')->name('file.update');
//     Route::delete('/file/{file}', 'FileController@destroy')->name('file.destroy');
//     // todo
//     Route::get('/files/no-author', 'FileController@todoAuthors')->name('file.no-author');
    
//     Route::group(['middleware' => ['permission:manage users']], function () {
//         Route::get('/users', 'UserController@index')->name('user.index');
//         Route::get('/user/new', 'UserController@create')->name('user.create');
//         Route::post('/user/new', 'UserController@store')->name('user.store');
//         Route::get('/user/{user}', 'UserController@edit')->name('user.edit');
//         Route::put('/user/{user}', 'UserController@update')->name('user.update');
//         Route::delete('/user/{user}', 'UserController@destroy')->name('user.destroy');
//     });
    
//     // Tag
//     Route::get('/tags', 'TagController@index')->name('tag.index');
//     Route::get('/tag/new', 'TagController@create')->name('tag.create');
//     Route::post('/tag/new', 'TagController@store')->name('tag.store');
//     Route::get('/tag/{tag}', 'TagController@edit')->name('tag.edit');
//     Route::put('/tag/{tag}', 'TagController@update')->name('tag.update');
//     Route::delete('/tag/{tag}', 'TagController@destroy')->name('tag.destroy');

// });

// // admin routing for authors (using different Controllers with content restriction)
// Route::group(['prefix' => 'admin', 'middleware' => 'role:autor', 'namespace' => 'AuthorRestricted'], function () {
    
// });