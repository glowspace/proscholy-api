<?php

namespace App\Helpers;

class OpenSongParser 
{
    protected $xmlElement;

    public function __construct($file_contents)
    {
        // $content = file_get_contents($file_path);
        $this->xmlElement = simplexml_load_string($file_contents);
    }

    public function getSongName()
    {
        return (string)$this->xmlElement->title;
    }

    public function getLyrics()
    {
        $str = (string)$this->xmlElement->lyrics;

        $match_verse = '/\[V([0-9])\]\n?/';
        $match_single_verse = '/\[V\]\n?/';
        $match_refr = '/\[C([0-9]?)\]\n?/';
        $match_bridge = '/\[B([0-9]?)\]\n?/';
        $match_intro = '/\[P\]/';
        $match_verse_break = '/\|\|/';

        $str = preg_replace(
            array(
                '/\n /',
                $match_verse,
                $match_single_verse,
                $match_refr,
                $match_bridge,
                $match_intro,
                $match_verse_break),

            array(
                "\n",
                '\1. ',
                '',
                'R\1: ',
                'B\1: ', 
                '',
                ''),
            $str
        );

        return trim($str);
    }
}