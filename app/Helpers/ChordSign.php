<?php

namespace App\Helpers;

use Log;

class ChordSign
{
    protected $baseNote;
    protected $baseNoteAccidental;
    protected $variant; // "", "mi", "m", "dim"
    protected $extension; // 7, maj7, ...
    protected $bassNote;
    protected $bassNoteAccidental;
    protected $optional;

    protected function __construct(
        $baseNote,
        $baseNoteAccidental,
        $variant,
        $extension,
        $bassNote,
        $bassNoteAccidental,
        $optional = false
    ) {
        $this->baseNote = $baseNote;
        $this->baseNoteAccidental = $baseNoteAccidental;
        $this->variant = $variant;
        $this->extension = $extension;
        $this->bassNote = $bassNote;
        $this->bassNoteAccidental = $bassNoteAccidental;
        $this->optional = $optional;
    }

    public function getBase()
    {
        return $this->baseNote . $this->baseNoteAccidental;
    }

    public function getVariant()
    {
        return $this->variant;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getBassNote()
    {
        return $this->bassNote . $this->bassNoteAccidental;
    }

    public function isOptional()
    {
        return $this->optional;
    }

    public static function parseFromText($text)
    {
        $optional = false;
        // the chord is in brackets
        if (preg_match("/\(([^\)]+)\)/", $text, $matches_brackets)) {
            $text = $matches_brackets[1];
            $optional = true;
        }

        $p_baseNote = "([A-H])(\#|b|is|es|s)?"; // base note with accidental
        $p_variant = "(mi|m|dim|\+)?";
        $p_ext = "([^\/]*)"; // everything but '/'
        $p_bass = "(\/([A-H47])(\#|b|is|es|s)?)?"; // bass note with accidental, or special case of chords 4/7 7/4 (this is handled later on)

        preg_match("/$p_baseNote$p_variant$p_ext$p_bass/", $text, $matches);

        // case of [], [%] and other weird stuff
        // TODO: IMPLEMENT [%] REPLACEMENT HANDLING
        if (count($matches) == 0) {
            return self::EMPTY();
        }

        // handle /4 and /7 chords
        if (count($matches) > 6 && is_numeric($matches[6])) {
            $matches[4] .= '/' . $matches[6];
            $matches[6] = "";
        }

        // handle 'maj' "irregular" exception
        if ($matches[3] == "m" && strlen($matches[4]) > 0 && substr_compare($matches[4], "aj", 0, 2) == 0) {
            $matches[3] = "";
            $matches[4] = "m" . $matches[4];
        }

        // handle 'sus' irregular exception 
        // - case Asus4 e.g. is not As + us4, but A + sus4
        if ($matches[2] == 's' && strlen($matches[4]) > 0 && substr_compare($matches[4], 'us', 0, 2) == 0) {
            $matches[2] = '';
            $matches[4] = 's' . $matches[4];
        }

        // rewrite 'Xis' to 'X#'
        if ($matches[2] == "is") {
            $matches[2] = "#";
        }
        if (count($matches) > 7 && $matches[7] == "is") {
            $matches[7] = "#";
        }
        // rewrite 'Xes' to 'Xb'
        if ($matches[2] == "es" || $matches[2] == "s") {
            $matches[2] = "b";
        }
        if (count($matches) > 7 && ($matches[7] == "es" || $matches[2] == "s")) {
            $matches[7] = "b";
        }

        $_bn = $matches[1];
        $_bna = $matches[2];
        $_v = $matches[3];
        $_e = $matches[4];
        $_bsn = count($matches) > 6 ? $matches[6] : "";
        $_bsna = count($matches) > 7 ? $matches[7] : "";

        return new ChordSign($_bn, $_bna, $_v, $_e, $_bsn, $_bsna, $optional);
    }

    public static function EMPTY()
    {
        return new ChordSign("", "", "", "", "", "");
    }
}
