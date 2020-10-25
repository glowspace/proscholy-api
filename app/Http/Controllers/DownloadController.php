<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\File;
use App\External;

class DownloadController extends Controller
{
    public function downloadFileOld(Request $request, $db_file_id, $filename)
    {
        // the $db_file_id references to and old table `files`, no longer being used
        // $filename should match the name of the stored file in public_files folder

        return $this->downloadFile($request, $filename);
    }

    public function downloadFile(Request $request, $filename)
    {
        $path = Storage::path("public_files/$filename");

        if (!file_exists($path)) {
            return response("Soubor nebyl nalezen", 404);
        }

        if ($request->get('stahnout') || $request->get('download') || $request->get('s') || $request->get('d')) {
            return response()->download($path, $filename);
        }

        return response()->file($path);
    }

    public function proxyExternal(External $external)
    {
        // todo check if media type is fine

        // return response()->download()
        if ($external->is_uploaded) {
            return response()->file($external->filepath);
        }

        // todo: use proper mime types
        $headers = [
            'Content-Type' => str_replace('file', 'application', $external->media_type),
            'Content-Disposition' => 'inline; filename=' . str_replace('file/', 'nahled.', $external->media_type) . ';'
        ];

        // "stream download" the response
        return response()->stream(function () use ($external) {
            echo file_get_contents($external->url);
        }, 200, $headers);
    }
}
