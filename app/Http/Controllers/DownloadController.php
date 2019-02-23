<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\File;

class DownloadController extends Controller
{
    public function downloadFile(File $file)
    {
        $fullPath = Storage::path($file->path);
        return response()->download($fullPath, $file->filename);
    }
}
