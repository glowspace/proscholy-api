<?php

namespace App\Helpers;

use Log;

class SongLine{

    protected $text = ""; 
    protected $chords = [];

    function __construct($text)
    {
        $this->text = $text;
        $this->processChords();
    }

    private function processChords()
    {
        $currentChordText = "";
        $line = trim($this->text);

        // starting of a line, notify Chord "repeater" if we are in a verse
        // if (strlen($line) > 0 && is_numeric($line[0])) {
        //     $chordQueue->notifyVerse($line[0]);
        // }

        for ($i = 0; $i < strlen($line); $i++) {
            if ($line[$i] == "[") {
                if ($currentChordText != "")
                    $this->chords[] = Chord::parseFromText($currentChordText, null);
                $currentChordText = "";
            }

            $currentChordText .= $line[$i];
        }

        $this->chords[] = Chord::parseFromText($currentChordText, null);

        // $string = "";
        // foreach ($chords as $chord)
        //     $string .= $chord->toHTML();

        // return $string;
    }

    public function toHTML()
    {
        $html = '<div class="song-line">';
        
        foreach ($this->chords as $ch) {
            $html .= $ch->toHTML();
        }

        $html .= '</div>';

        return $html;
    }
}
