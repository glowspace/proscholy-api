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
Route::get('/material/{external}', 'DownloadController@proxyExternal')->name('external.proxy');

Route::group(['prefix' => 'be-api'], function () {
    Route::get('lilypond-download-source', 'DownloadController@downloadLilypondSource');
    Route::get('lilypond-download-parts-source', 'DownloadController@downloadLilypondPartsSource');
});



Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {
    Route::group(['middleware' => 'role:admin|editor|autor', 'namespace' => 'Admin'], function () {
        Route::get('/', 'AdminController@renderDash')->name('dashboard');

        Route::resource('external', 'ExternalController')->only(['index', 'edit']);
        Route::resource('author', 'AuthorController')->only(['index', 'edit']);
        Route::resource('tag', 'TagController')->only(['index', 'edit']);
        Route::resource('songbook', 'SongbookController')->only(['index', 'edit']);
        Route::resource('news-item', 'NewsItemController')->only(['index', 'edit']);

        Route::get('/songs', 'SongController@index')->name('song.index');
        Route::get('/song/{song_lyric}/edit', 'SongController@edit')->name('song.edit');

        Route::resource('tag', 'TagController')->except(['show']);

        Route::group(['middleware' => ['permission:manage users']], function () {
            Route::resource('user', 'UserController')->except(['show']);
        });

        Route::get('testdb', 'AdminController@testDb');
    });
});

// refreshing
Route::get('/refresh-updating/{type}/{id}', 'Api\LockController@refresh_updating');

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

// Route::get('/run-schedule', function () {
//     Artisan::call('schedule:run --no-ansi');
//     return response("Scheduled tasks have run");
// });

Route::get('/regenschori', function () {
    return redirect(config('url.regenschori'));
})->name('client.regenschori');
