<?php

namespace App\Helpers;

use Log;

class SongLine {

    protected $text = ""; 
    protected $chords = [];
    protected $ch_queue;

    function __construct($text, ChordQueue $ch_queue)
    {
        $this->text = $text;
        $this->ch_queue = $ch_queue;
        $this->ch_queue->notifyNewLine(); // note: this does nothing as for now
        $this->processChords();
    }

    private function processChords()
    {
        $currentChordText = "";
        $line = trim($this->text);

        for ($i = 0; $i < strlen($line); $i++) {
            if ($line[$i] == "[") {
                if ($currentChordText != "")
                    $this->chords[] = Chord::parseFromText($currentChordText, $this->ch_queue);
                $currentChordText = "";
            }

            $currentChordText .= $line[$i];
        }

        $this->chords[] = Chord::parseFromText($currentChordText, $this->ch_queue);

        // $string = "";
        // foreach ($chords as $chord)
        //     $string .= $chord->toHTML();

        // return $string;
    }

    public function toHTML($songPartTag = null)
    {
        $html = '<div class="song-line">';

        if (isset($songPartTag)) {
            $html .= '<song-part-tag>' . $songPartTag . '</song-part-tag>';
        }
        
        foreach ($this->chords as $ch) {
            $html .= $ch->toHTML();
        }

        $html .= '</div>';

        return $html;
    }
}
