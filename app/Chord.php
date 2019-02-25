<?php

namespace App;

class Chord{
    protected $chordSign; // ChordSign
    protected $text;

    public function __construct($chordSign, $text){
        $this->chordSign = $chordSign;
        $this->text = $text;
    }

    public function toHTML(){
        // TODO: format with https://laravelcollective.com/docs/5.4/html

        $html = '<chord';
        $html.= ' base="'.$this->chordSign->getBase().'"';
        $html.= ' variant="'.$this->chordSign->getVariant().'"';
        $html.= ' extension="'.$this->chordSign->getExtension().'"';
        $html.= ' bass="'.$this->chordSign->getBassNote().'"';
        $html.= ' is-divided="'.$this->isDivided().'"';
        $html.= '>';
        $html.= rtrim($this->text);
        $html.= "</chord>";

        if (!$this->isDivided())
            $html.= " ";

        return $html;
    }

    public function isDivided(){
        if (strlen($this->text) == 0) return false;

        $last = $this->text[strlen($this->text) - 1];

        $endingValues = [" ", ".", ",", ";", "\r", ":", "!", "?", "-"];

        return !in_array($last, $endingValues);
    }
    
    public static function parseFromText($chordText){
        if (strlen($chordText) > 0 && $chordText[0] == "["){
            $index = 1;
            $chordSignText = "";

            // we are in the brackets
            while ($chordText[$index] != ']'){
                $chordSignText .= $chordText[$index];
                $index++;
            }

            $text = substr($chordText, $index + 1);
            $chordSign = ChordSign::parseFromText($chordSignText);
        } else{
            // empty chord sign
            $text = $chordText;
            $chordSign = ChordSign::EMPTY();
        }

        return new Chord($chordSign, $text);
    }
}
