<?php

namespace App\Helpers;

class ChordQueue
{
    protected $verse = [];
    protected $refrain = [];
    protected $mode = 0;

    protected $MODE_DEFAULT = 0;
    protected $MODE_STORE_VERSE = 1;
    // protected $MODE_STORE_REFRAIN = 2;
    protected $MODE_READ_VERSE = 3;
    // protected $MODE_READ_REFRAIN = 4;

    protected $index = 0;
    
    public function __construct()
    {

    }

    public function notifyVerse($n)
    {
        if ($n == 1) {
            $this->mode = $this->MODE_STORE_VERSE;
        } else {
            $this->mode = $this->MODE_READ_VERSE;
        }

        $this->index = 0;
    }

    // public function notifyRefrain($n)
    // {
    //     if ($n == 1) {
    //         $this->mode = $this->MODE_STORE_REFRAIN;
    //     } else {
    //         $this->mode = $this->MODE_READ_REFRAIN;
    //     }

    //     $this->index = 0;
    // }

    // ?

    public function notifyNewLine()
    {
        
    }

    public function processChordSignText($chord_string)
    {
        if ($this->mode === $this->MODE_STORE_VERSE) {
            $this->verse[] = $chord_string;
        }
        // if ($this->mode === $this->MODE_STORE_REFRAIN) {
        //     $this->refrain[] = $chord_string;
        // }
    }

    public function getChordSignText()
    {
        $chord = "";

        if ($this->mode === $this->MODE_READ_VERSE && count($this->verse) > $this->index) {
            $chord = $this->verse[$this->index];
        }
        // if ($this->mode === $this->MODE_READ_REFRAIN  && count($this->refrain) > $this->index) {
        //     $chord = $this->refrain[$this->index];
        // }
        $this->index += 1;
        return $chord;
    }
}