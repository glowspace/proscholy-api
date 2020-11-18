<?php

namespace App\Helpers;

class SongLyricHelper
{
    public static function getLyricsRepresentation($song_lyric)
    {
        $parts = [];

        $lines = explode("\n", $song_lyric->lyrics);

        $chQueue = new ChordQueue();

        foreach ($lines as $l) {
            // determine wheter the line starts with a sequence matching a new song part
            // e.g. R:, B:, 2: etc.
            $line = trim($l);

            // prelude or interlude
            if (strlen($line) > 0 && $line[0] == '@') {
                if ($pos = strpos(strtolower($line), 'předehra:')) {
                    $p = new SongPart('P', $chQueue);
                    $p->appendLine(substr($line, $pos + strlen('předehra:')));
                    $parts[] = $p;
                }

                if ($pos = strpos(strtolower($line), 'mezihra:')) {
                    $p = new SongPart('M', $chQueue);
                    $p->appendLine(substr($line, $pos + strlen('mezihra:')));
                    $parts[] = $p;
                }
                continue;
            }

            // hidden parts
            if (preg_match('/^\(([RBC])\:\)/', $line, $matches)) {
                $p = new SongPart($matches[1], $chQueue, true);
                $parts[] = $p;
                continue;
            }

            // normal parts
            if (preg_match('/^([RBC\d]\d?)[\:\.](.*)/', $line, $matches)) {
                $p = new SongPart($matches[1], $chQueue);
                $p->appendLine($matches[2]);
                $parts[] = $p;
                continue;
            }

            // apparently not beginning with a marker
            // so first check if we have added any part yet
            if (count($parts) == 0)
                $parts[] = new SongPart("", $chQueue);

            $activePart = $parts[count($parts) - 1];
            $activePart->appendLine($line);
        }

        return $parts;
    }
}
