<?php

/**
 * Administration
 */
Route::get('/logout', 'Auth\LoginController@logout')->name('auth.logout');
Auth::routes(['register' => false]);

// Downloading
Route::get('/download/{file}/{filename?}', 'DownloadController@downloadFile')->name('download.file');
Route::get('/preview/{file}/{filename?}', 'DownloadController@previewFile')->name('preview.file');

// Thumbnails for pdf files
Route::get('/thumbnail/external/{external}', 'DownloadController@getThumbnailExternal')->name('external.thumbnail');
Route::get('/thumbnail/{file}/{filename?}', 'DownloadController@getThumbnailFile')->name('file.thumbnail');

/**
 * Admin routes.
 */
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

/**
 * Refreshing routes.
 *
 * TODO: This is some obscure Mira's stuff. :D
 */
Route::get('/refresh-updating/song-lyric/{song_lyric}', 'Api\LockController@refresh_updating_song_lyric');
Route::get('/refresh-updating/songbook/{songbook}', 'Api\LockController@refresh_updating_songbook');
