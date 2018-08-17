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
 * Veřejné zobrazení zpěvníku.
 */
Route::get('/home', 'PublicController@renderHome')->name('home');
Route::get('/seznam-pisni', 'ListController@renderSongListAlphabetical')->name('song.list');
Route::get('/seznam-autoru', 'ListController@renderAuthorListAlphabetical')->name('author.list');
Route::get('/pisen/o{id}', 'SongController@renderSong')->name('song.single');
Route::get('/pisen/p{id}', 'TranslationController@renderTranslation')->name('translation.single');
Route::get('/autor/{id}', 'AuthorController@renderAuthor')->name('author.single');


Route::get('/navrh/preklad/{id}', 'TranslationController@renderTranslation')->name('request.new.song');


/**
 * Administrace.
 */
Route::group(['prefix' => 'admin'], function ()
{
    Route::get('/manage/', 'AdminController@renderDash')->name('admin.dashboard');
    Route::get('/manage/song/new', 'AdminController@renderNewSong')->name('admin.song.new');
    Route::post('/manage/insert', 'AdminController@storeNewSong')->name('admin.song.new.save');

    // Video
    Route::get('/manage/videos', 'AdminController@renderVideos')->name('admin.videos');
    Route::get('/manage/video/new', 'AdminController@renderVideoCreate')->name('admin.video.new');
    Route::post('/manage/video/new/save', 'AdminController@storeVideoCreate')->name('admin.video.new.save');
    Route::get('/manage/video/edit/{id}', 'AdminController@renderVideoEdit')->name('admin.video.edit');
    Route::post('/manage/video/edit/save', 'AdminController@storeVideoEdit')->name('admin.video.edit.save');
    Route::get('/manage/video/edit/{id}/translation', 'AdminController@renderVideoEditTranslation')
        ->name('admin.video.edit.translation');
    Route::get('/manage/video/edit/{id}/translation/{t_id}', 'AdminController@storeVideoEditTranslation')
        ->name('admin.video.edit.translation.save');
    Route::get('/manage/video/edit/{id}/author', 'AdminController@renderVideoEditAuthor')->name('admin.video.edit.author');
    Route::get('/manage/video/edit/{id}/author/{a_id}', 'AdminController@storeVideoEditAuthor')->name('admin.video.edit.author.save');

    Voyager::routes();
});
