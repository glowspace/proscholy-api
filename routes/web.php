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
Route::get('/', 'Client\PublicController@renderHome')->name('client.home');

Route::get('/seznam-pisni', 'Client\ListController@renderSongListAlphabetical')->name('client.song.list');
Route::get('/seznam-autoru', 'Client\ListController@renderAuthorListAlphabetical')->name('client.author.list');

// Client single model views
Route::get('/pisen/{SongLyric}/text', 'Client\SongLyricsController@songText')->name('client.song.text');
Route::get('/pisen/{SongLyric}/noty', 'Client\SongLyricsController@songScore')->name('client.song.score');
Route::get('/pisen/{SongLyric}/preklady', 'Client\SongLyricsController@songOtherTranslations')->name('client.song.translations');
Route::get('/pisen/{SongLyric}/nahravky', 'Client\SongLyricsController@songAudioRecords')->name('client.song.audio_records');
Route::get('/pisen/{SongLyric}/videa', 'Client\SongLyricsController@songExternals')->name('client.song.externals');
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
 *
 * TODO: převést administraci do resources
 */
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function ()
{
    Route::get('/manage/', 'AdminController@renderDash')->name('admin.dashboard');
    Route::get('/manage/todo', 'AdminController@renderTodo')->name('admin.todo');
    Route::get('/manage/todo/song/setAuthor/{author_id}/{song_id}/', 'AdminController@setSongAuthor')
        ->name('admin.todo.setSongAuthor');

    // External
    Route::get('/manage/externals', 'AdminController@renderExternals')->name('admin.externals');
    Route::get('/manage/external/new', 'AdminController@renderExternalCreate')->name('admin.external.new');
    Route::post('/manage/external/new/save', 'AdminController@storeExternalCreate')->name('admin.external.new.save');
    Route::get('/manage/external/edit/{id}', 'AdminController@renderExternalEdit')->name('admin.external.edit');
    Route::post('/manage/external/edit/save', 'AdminController@storeExternalEdit')->name('admin.external.edit.save');
    Route::get('/manage/external/edit/{id}/translation', 'AdminController@renderExternalEditTranslation')
        ->name('admin.external.edit.translation');
    Route::get('/manage/external/edit/{id}/translation/{t_id}', 'AdminController@storeExternalEditTranslation')
        ->name('admin.external.edit.translation.save');
    Route::get('/manage/external/edit/{id}/author', 'AdminController@renderExternalEditAuthor')->name('admin.external.edit.author');
    Route::get('/manage/external/edit/{id}/author/{a_id}', 'AdminController@storeExternalEditAuthor')
        ->name('admin.external.edit.author.save');

    // Song
    Route::get('/manage/songs', 'AdminController@renderSongs')->name('admin.songs');
    Route::get('/manage/song/new', 'AdminController@renderNewSong')->name('admin.song.new');
    Route::post('/manage/insert', 'AdminController@storeNewSong')->name('admin.song.new.save');
    Route::get('/manage/song/{id}', 'AdminController@renderSongEdit')->name('admin.song.edit');
    Route::post('/manage/song/save', 'AdminController@storeSongEdit')->name('admin.song.edit.save');
    Route::get('/manage/song/{id}/add_author', 'AdminController@renderAddSongAuthor')->name('admin.song.author.add');
    Route::get('/manage/song/{id}/remove_author/{author_id}', 'AdminController@storeRemoveSongAuthor')
        ->name('admin.song.author.remove');

    // Translation
    Route::get('/manage/translation/new', 'AdminController@renderNewSong')->name('admin.translation.new');
    Route::post('/manage/translation/new', 'AdminController@renderNewSong')->name('admin.translation.new.save');
    Route::get('/manage/translation/{id}', 'AdminController@renderNewSong')->name('admin.translation.edit');

    // Author
    Route::get('/manage/author/new', 'AdminController@renderNewAuthor')->name('admin.author.new');
    Route::post('/manage/author/new', 'AdminController@storeNewAuthor')->name('admin.author.new.save');
});