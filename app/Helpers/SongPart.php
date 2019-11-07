<?php

namespace App\Helpers;

use Log;

class SongPart{

    protected $type;
    protected $is_hidden;
    protected $inner_text = ""; 
    protected $song_lines = [];

    function __construct($type, $is_hidden = false)
    {
        $this->type = $type;
        $this->is_hidden = $is_hidden;
    }

    function isHidden() {
        return $this->isHidden();
    }

    function getType() {
        return $this->type;
    }

    function isVerse() {
        return is_numeric($this->type);
    }

    function isUndefined() {
        return $this->type == "";
    }

    function appendLine($line) {
        $this->inner_text .= $line . '\n';
        $this->song_lines[] = new SongLine($line);
    }

    function toHTML()
    {
        $html = '<div class="song-part">';

        foreach ($this->song_lines as $l) {
            $html .= $l->toHTML();
        }

        $html .= '</div>';

        return $html;
    }

    public function __toString()
    {
        return $this->type . ": " . $this->inner_text;
    }
}
