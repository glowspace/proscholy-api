<?php

namespace App\Helpers;

use Log;

class ChordSign{
    protected $baseNote;
    protected $baseNoteAccidental;
    protected $variant; // "", "mi", "m", "dim"
    protected $extension; // 7, maj7, ...
    protected $bassNote;
    protected $bassNoteAccidental;

    protected function __construct($baseNote, $baseNoteAccidental, $variant, $extension, $bassNote, $bassNoteAccidental){
        $this->baseNote = $baseNote;
        $this->baseNoteAccidental = $baseNoteAccidental;
        $this->variant = $variant;
        $this->extension = $extension;
        $this->bassNote = $bassNote;
        $this->bassNoteAccidental = $bassNoteAccidental;
    }

    public function getBase(){
        return $this->baseNote.$this->baseNoteAccidental;
    }

    public function getVariant(){
        return $this->variant;
    }

    public function getExtension(){
        return $this->extension;
    }

    public function getBassNote(){
        return $this->bassNote.$this->bassNoteAccidental;
    }

    public static function parseFromText($text){
        $p_baseNote = "([A-H])(\#|b|is)?"; // base note with accidental
        $p_variant = "(mi|m|dim|\+)?";
        $p_ext = "([^\/]*)"; // everything but /
        $p_bass = "(\/([A-H])(\#|b)?)?"; // bass note with accidental

        preg_match("/$p_baseNote$p_variant$p_ext$p_bass/", $text, $matches);

        // case of [], [%] and other weird stuff
        // TODO: IMPLEMENT [%] REPLACEMENT HANDLING
        if (count($matches) == 0) {
            return self::EMPTY();
        }

        // handle maj "irregular" exception
        if ($matches[3] == "m" && strlen($matches[4]) > 0 && substr_compare($matches[4], "aj", 0, 2) == 0) {
            $matches[3] = "";
            $matches[4] = "m".$matches[4];
        }

        // rewrite Xis to X#
        if ($matches[2] == "is") {
            $matches[2] = "#";
        }

        $_a = $matches[1];
        $_b = $matches[2];
        $_c = $matches[3];
        $_d = $matches[4];
        $_f = count($matches) > 6 ? $matches[6] : ""; 
        $_g = count($matches) > 7 ? $matches[7] : ""; 

        return new ChordSign($_a, $_b, $_c, $_d, $_f, $_g);

        // if (count($matches) == 8){
        //     // chord with bass note
        //     return new ChordSign($matches[1], $matches[2], $matches[3], $matches[4], $matches[6], $matches[7]);
        // } else if (count($matches) == 5) {
        //     return new ChordSign($matches[1], $matches[2], $matches[3], $matches[4], "", "");
        // } else{
        //     Log::error("invalid chord from $text");
        //     return self::EMPTY();
        // }
    }

    public static function EMPTY(){
        return new ChordSign("", "", "", "", "", "");
    }
}
