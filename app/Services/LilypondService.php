<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class LilypondService
{
    public function getLilypondSvg($lilypond)
    {
        $endpoint = config('lilypond.host') . ":" . config('lilypond.port') . '/make?recipe=svgcrop';

        $client = new Client();
        $res = $client->post($endpoint, [
            'multipart' => [
                [
                    'name'     => 'file_lilypond', // input name, needs to stay the same
                    'contents' => $lilypond,
                    'filename' => 'score.ly' // doesn't matter
                ]
            ]
        ]);

        // gets the JSON

        if ($res->getStatusCode() == 200) {
            return $res->getBody();
        }

        throw new Exception("Error getting svg", $res->getStatusCode());
    }
}
