<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\External;
use App\Services\LilypondPartsService;
use App\Services\LilypondService;

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

    // todo: refactor this to graphql
    public function downloadLilypondSource(Request $request)
    {
        $filename = $request->get('filename');
        $lilypond = $request->get('lilypond_src');
        $key_major = $request->get('lylipond_key_major');

        $ly_s = new LilypondService();
        $src = $ly_s->makeLilypondBasicTemplate($lilypond, $key_major);

        $headers = [
            'Content-Type' => 'text/*'
        ];

        return response()->streamDownload(function () use ($src) {
            echo $src;
        }, "$filename.ly", $headers);
    }

    // todo: refactor this to graphql
    public function downloadLilypondPartsSource(Request $request)
    {
        $parts = json_decode($request->get('lilypond_parts'), true);
        $global_src = $request->get('global_src') ?? '';
        $sequence_string = $request->get('sequence_string') ?? '';
        $score_config = json_decode($request->get('score_config'), true);

        /** @var LilypondPartsService */
        $ly_s = app(LilypondPartsService::class);
        $src = $ly_s->makeLilypondPartsTemplate($parts, $global_src, $score_config, $sequence_string);

        $headers = [
            'Content-Type' => '	application/zip'
        ];

        return response()->streamDownload(function () use ($src) {
            echo stream_get_contents($src->getZippedSrcStream());
        }, "score.zip", $headers);
    }

    public function proxyExternal(External $external)
    {
        if ($external->mime_type == "") {
            return response()->redirect($external->url);
        }

        if ($external->is_uploaded) {
            return response()->file(Storage::path($external->filepath));
        }

        $headers = [
            'Content-Type' => $external->mime_type,
            'Content-Disposition' => 'inline; filename=' . str_replace('file/', 'nahled.', $external->media_type) . ';'
        ];

        // "stream download" the response
        return response()->stream(function () use ($external) {
            echo file_get_contents($external->url);
        }, 200, $headers);
    }
}
