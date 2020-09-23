<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\File;
use App\External;

class DownloadController extends Controller
{
    public function downloadFileOld($db_file_id, $filename)
    {
        // the $db_file_id references to and old table `files`, no longer being used
        // $filename should match the name of the stored file in public_files folder

        return $this->downloadFile($filename);
    }

    public function downloadFile(Request $request, $filename)
    {
        $path = Storage::path("public_files/$filename");

        if (!file_exists($path)) {
            return response("Soubor nebyl nalezen", 404);
        }

        if ($request->get('nahled')) {
            return response()->file($path);
        }

        return response()->download($path, $filename);
    }

    // public function previewFile(File $file)
    // {
    //     $fullPath = Storage::path($file->path);

    //     if (!file_exists($fullPath)) {
    //         return response("Soubor nebyl nalezen", 404);
    //     }

    //     return response()->file($fullPath);
    // }

    // todo: remove
    public function getThumbnailFile(File $file)
    {
        if (!$file->canHaveThumbnail()) {
            return response('No thumbnail available', 404);
        }

        $fullPath = Storage::path($file->getThumbnailPath());

        if (!file_exists($fullPath)) {
            return response("Soubor nebyl nalezen", 404);
        }
        return response()->file($fullPath);
    }
}
