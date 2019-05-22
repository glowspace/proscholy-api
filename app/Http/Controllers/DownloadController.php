<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\File;
use App\External;

class DownloadController extends Controller
{
    public function downloadFile(File $file)
    {
        $file->downloads = $file->downloads + 1;
        $file->save();

        $fullPath = Storage::path($file->path);
        return response()->download($fullPath, $file->filename);
    }

    public function previewFile(File $file)
    {
        $fullPath = Storage::path($file->path);
        return response()->file($fullPath);
    }

    public function getThumbnailFile(File $file)
    {
        if (!$file->canHaveThumbnail()) {
            return response('No thumbnail available', 404);
        }

        $fullPath = Storage::path($file->getThumbnailPath());
        return response()->file($fullPath);
    }

    // public function getThumbnailExternal(External $external)
    // {
    //     if (!$external->canHaveThumbnail()) {
    //         return response('No thumbnail available', 404);
    //     }

    //     $fullPath = Storage::path($external->getThumbnailPath());
    //     return response()->file($fullPath);
    // }
}
