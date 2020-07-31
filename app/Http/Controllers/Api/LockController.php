<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Songbook;
use App\SongLyric;

class LockController extends Controller
{
    public function refresh_updating($type, $id)
    {
        $models = array(
            'song' => 'App\SongLyric',
            'songbook' => 'App\Songbook'
        );

        if (!isset($models[$type])) {
            return response('Class not found', 404)->header('Content-Type', 'text/plain');
        }

        $model = $models[$type]::find($id);

        if (!isset($model)) {
            return response('Model not found', 404)->header('Content-Type', 'text/plain');
        }

        if ($model->isLocked()) {
            return response('Locked', 200)->header('Content-Type', 'text/plain');
        }

        $model->lock(true);
        return response('OK', 200)->header('Content-Type', 'text/plain');
    }
}
