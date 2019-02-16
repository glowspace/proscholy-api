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

// Redirects to real search route.
Route::post('/vyhledavani/send_search', 'Client\SearchController@searchSend')->name('client.search');
// The real user search route
Route::get('/vyhledavani/{phrase}', 'Client\SearchController@searchResults')->name('client.search_results');

Route::get('/seznam-pisni', 'Client\ListController@renderSongListAlphabetical')->name('client.song.list');
Route::get('/seznam-autoru', 'Client\ListController@renderAuthorListAlphabetical')->name('client.author.list');

// Client single model views
Route::get('/pisen/{song_lyric}/text', 'Client\SongLyricsController@songText')->name('client.song.text');
Route::get('/pisen/{song_lyric}/noty', 'Client\SongLyricsController@songScore')->name('client.song.score');
Route::get('/pisen/{song_lyric}/preklady', 'Client\SongLyricsController@songOtherTranslations')->name('client.song.translations');
Route::get('/pisen/{song_lyric}/nahravky', 'Client\SongLyricsController@songAudioRecords')->name('client.song.audio_records');
Route::get('/pisen/{song_lyric}/videa', 'Client\SongLyricsController@songExternals')->name('client.song.externals');
Route::get('/autor/{Author}', 'Client\AuthorController@renderAuthor')->name('client.author');
// TODO: Songbook view
Route::get('/zpevnik/{Songbook}', 'Client\SongbookController@renderSongbook')->name('client.songbook');

// Client forms
// TODO: Public content request
Route::get('/navrh/{id}', 'RequestController@request')->name('client.request');
Route::post('/navrh/{id}', 'RequestController@storeRequest')->name('client.request');

// TODO: Report song licence abuse
Route::get('/report', 'Client\ReportController@report')->name('client.report');
Route::post('/report', 'Client\ReportController@storeReport')->name('client.report');

Auth::routes(['register' => true]);
Route::get('/logout', 'Auth\LoginController@logout');

/**
 * Administrace.
 */
Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'Admin'], function ()
{
    Route::get('/manage/', 'AdminController@renderDash')->name('admin.dashboard');
    Route::get('/manage/todo', 'AdminController@renderTodo')->name('admin.todo');
    Route::get('/manage/todo/song/setAuthor/{author_id}/{song_id}/', 'AdminController@setSongAuthor')
        ->name('admin.todo.setSongAuthor');

    // // External
    // Route::get('/manage/externals', 'AdminController@renderExternals')->name('admin.externals');
    // Route::get('/manage/external/new', 'AdminController@renderExternalCreate')->name('admin.external.new');
    // Route::post('/manage/external/new/save', 'AdminController@storeExternalCreate')->name('admin.external.new.save');
    // Route::get('/manage/external/edit/{id}', 'AdminController@renderExternalEdit')->name('admin.external.edit');
    // Route::post('/manage/external/edit/save', 'AdminController@storeExternalEdit')->name('admin.external.edit.save');
    // Route::get('/manage/external/edit/{id}/translation', 'AdminController@renderExternalEditTranslation')
    //     ->name('admin.external.edit.translation');
    // Route::get('/manage/external/edit/{id}/translation/{t_id}', 'AdminController@storeExternalEditTranslation')
    //     ->name('admin.external.edit.translation.save');
    // Route::get('/manage/external/edit/{id}/author', 'AdminController@renderExternalEditAuthor')->name('admin.external.edit.author');
    // Route::get('/manage/external/edit/{id}/author/{a_id}', 'AdminController@storeExternalEditAuthor')
    //     ->name('admin.external.edit.author.save');

    // Song
    Route::get('/manage/songs', 'SongController@index')->name('admin.song.index');
    Route::get('/manage/song/new', 'SongController@create')->name('admin.song.create');
    Route::post('/manage/insert', 'SongController@store')->name('admin.song.store');
    Route::get('/manage/song/{song_lyric}', 'SongController@edit')->name('admin.song.edit');
    Route::post('/manage/song/save', 'SongController@update')->name('admin.song.update');
    // TODO
    Route::get('/manage/song/{id}/add_author', 'SongController@renderAddSongAuthor')->name('admin.song.author.add');
    Route::get('/manage/song/{id}/remove_author/{author_id}', 'SongController@storeRemoveSongAuthor')
        ->name('admin.song.author.remove');

    // // Translation
    // Route::get('/manage/translation/new', 'AdminController@renderNewSong')->name('admin.translation.new');
    // Route::post('/manage/translation/new', 'AdminController@renderNewSong')->name('admin.translation.new.save');
    // Route::get('/manage/translation/{id}', 'AdminController@renderNewSong')->name('admin.translation.edit');

    // Author
    // Route::get('/manage/author/new', 'AdminController@renderNewAuthor')->name('admin.author.new');
    // Route::post('/manage/author/new', 'AdminController@storeNewAuthor')->name('admin.author.new.save');
});