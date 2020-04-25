<?php

namespace App\Helpers;

class Chord{
    protected $chordSign; // ChordSign
    protected $text;
    protected $isSubstitute;

    public function __construct($chordSign, $text, $isSubstitute = false) {
        $this->chordSign = $chordSign;
        $this->text = $text;
        $this->isSubstitute = $isSubstitute;
    }

    public function getBase() {
        return $this->chordSign->getBase();
    }

    public function getVariant() {
        return $this->chordSign->getVariant();
    }

    public function getExtension() {
        return $this->chordSign->getExtension();
    }

    public function getBass() {
        return $this->chordSign->getBassNote();
    }

    public function isOptional() {
        return $this->chordSign->isOptional();
    }

    public function getText() {
        return $this->text;
    }

    public function isSubstitute() {
        return $this->isSubstitute;
    }

    // todo: make obsolete
    public function toHTML(){
        $html = "";

        if (strlen($this->text) > 0 && $this->text[0] == " ") {
            $html.=" ";
        }

        $text_value = rtrim($this->text);
        // see song Amen - substitute "-" by " " for better readability
        if ($text_value == "-" && $this->chordSign->getBase() != "") {
            $text_value = "";
        }

        // hide everything else than text from google
        $html.= '<!--googleoff: all-->';

        $html.= '<chord';
        $html.= ' base="'.$this->chordSign->getBase().'"';
        $html.= ' variant="'.$this->chordSign->getVariant().'"';
        $html.= ' extension="'.$this->chordSign->getExtension().'"';
        $html.= ' bass="'.$this->chordSign->getBassNote().'"';
        $html.= ' is-divided="'.$this->isDivided().'"';
        $html.= ' is-substitute="'.$this->isSubstitute.'"';
        $html.= ' is-optional="'.$this->chordSign->isOptional().'"';
        $html.= '>';

        $html.= '<!--googleon: all-->';
        $html.= $text_value;
        $html.= "</chord>";

        if (!$this->isDivided())
            $html.= " ";

        return $html;
    }

    public function isDivided(){
        if (strlen($this->text) == 0) return false;

        $last = $this->text[strlen($this->text) - 1];

        $endingValues = [" ", ".", ",", ";", "\r", ":", "!", "?"];

        return !in_array($last, $endingValues);
    }
    
    public static function parseFromText($chordText, $chordQueue = null){
        $isSubstitute = false;

        if (strlen($chordText) > 0 && $chordText[0] == "["){
            $index = 1;
            $chordSignText = "";

            // we are in the brackets
            while ($chordText[$index] != ']'){
                $chordSignText .= $chordText[$index];
                $index++;
            }
            // the rest is a part of lyrics aka text
            $text = substr($chordText, $index + 1);

            // handle [%] substitute chords
            if (isset($chordQueue)) {
                if ($chordSignText == "%") {
                    $chordSignText = $chordQueue->getChordSignText();
                    $isSubstitute = true;
                } else {
                    $chordQueue->processChordSignText($chordSignText);
                }
            }

            $chordSign = ChordSign::parseFromText($chordSignText);

        } else{
            // empty chord sign
            $text = $chordText;
            $chordSign = ChordSign::EMPTY();
        }

        return new Chord($chordSign, $text, $isSubstitute);
    }
}
