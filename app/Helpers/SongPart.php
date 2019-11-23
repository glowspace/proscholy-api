<?php

namespace App\Helpers;
use Log;

class SongPart{

    protected $type;
    protected $is_hidden;
    protected $inner_text = ""; 
    protected $song_lines = [];
    protected $ch_queue;

    function __construct($type, ChordQueue $chQueue, $is_hidden = false)
    {
        $this->type = $type;
        $this->is_hidden = $is_hidden;
        $this->ch_queue = $chQueue;

        if ($this->isVerse()) {
            $this->ch_queue->notifyVerse($this->getVerseNumber());
        }
    }

    function isHidden() {
        return $this->is_hidden;
    }

    function getType() {
        return $this->type;
    }

    function getTypeString() {
        if ($this->isHidden()) {
            return "";
        }

        if ($this->isVerse()) {
            return $this->getVerseNumber() . '.&nbsp;';
        }

        if ($this->type == 'P') {
            return 'předehra:&nbsp;';
        }

        if ($this->type == 'M') {
            return 'mezihra:&nbsp;';
        }

        if ($this->type == "") {
            return "";
        }

        // bridge, refrain, coda
        return $this->type . ":&nbsp;";
    }

    function isVerse() {
        return is_numeric($this->type);
    }

    function getVerseNumber() : int {
        return (int)$this->type;
    }

    function isUndefined() {
        return $this->type == "";
    }

    function appendLine($line) {
        $this->inner_text .= $line . '\n';
        $this->song_lines[] = new SongLine($line, $this->ch_queue);
    }

    function toHTML()
    {
        $html = '<div class="song-part">';

        // $html .= '<div class="song-part-tag">' . $this->getTypeString() . '</div>';

        for ($i = 0; $i < count($this->song_lines); $i++) {
            $line = $this->song_lines[$i];

            if ($i == 0 && $this->type !== "") {
                $html .= $line->toHTML($this->getTypeString());
            } else {
                $html .= $line->toHTML();
            }
        }

        // foreach ($this->song_lines as $l) {
        //     $html .= $l->toHTML();
        // }

        $html .= '</div>';

        return $html;
    }

    public function __toString()
    {
        return $this->type . ": " . $this->inner_text;
    }
}
