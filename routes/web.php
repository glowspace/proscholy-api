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
Route::get('/', 'PublicController@renderHome')->name('home');
Route::get('/seznam-pisni', 'ListController@renderSongListAlphabetical')->name('song.list');
Route::get('/seznam-autoru', 'ListController@renderAuthorListAlphabetical')->name('author.list');
Route::get('/pisen/{id}', 'SongLyricsController@render')->name('song_lyrics.single');
Route::get('/autor/{id}', 'AuthorController@renderAuthor')->name('author.single');
Route::get('/zpevnik/{id}', 'SongbookController@renderSongbook')->name('songbook');


Route::get('/navrh/preklad/{id}', 'TranslationController@renderTranslation')->name('request.new.song');

Auth::routes(['register' => true]);


/**
 * Administrace.
 */
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function ()
{
    Route::get('/manage/', 'AdminController@renderDash')->name('admin.dashboard');
    Route::get('/manage/todo', 'AdminController@renderTodo')->name('admin.todo');
    Route::get('/manage/todo/song/setAuthor/{author_id}/{song_id}/', 'AdminController@setSongAuthor')
        ->name('admin.todo.setSongAuthor');


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
    Route::get('/manage/video/edit/{id}/author/{a_id}', 'AdminController@storeVideoEditAuthor')
        ->name('admin.video.edit.author.save');

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
