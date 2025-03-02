<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\External;
use App\Services\LilypondPartsService;
use App\Services\LilypondClientService;
use Illuminate\Support\Facades\DB;
use App\SongLyric;
use ZipStream\ZipStream;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function downloadLilyponds(Request $request) {
        $updated_after = $request->get('updated_after');

        $result = DB::table('song_lyrics')
            ->select('song_lyrics.id', 'rendered_scores.filename')
            ->join('lilypond_parts_sheet_music', 'song_lyrics.id', '=', 'lilypond_parts_sheet_music.song_lyric_id')
            ->join('rendered_scores', 'lilypond_parts_sheet_music.id', '=', 'rendered_scores.lilypond_parts_sheet_music_id')
            ->where('song_lyrics.updated_at', '>', $updated_after)
            ->where('rendered_scores.filetype', 'svg')
            ->where('render_config_hash', '491ed726') // {"hide_voices": ["muzi", "tenor", "bas", "zeny", "sopran", "alt"]}
            ->get();
            
        $response = new StreamedResponse(function () use ($result) {
            $zip = new ZipStream(
                outputName: 'svgs.zip',
                sendHttpHeaders: true,
            );

            foreach ($result as $row) {
                $zip->addFileFromPath(
                    fileName: "$row->id.svg.gz", // name the files according to the song lyric IDs
                    path: Storage::path("rendered_scores/$row->filename.svg.gz"),
                );
            }

            $zip->finish();
        });

        return $response;
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
