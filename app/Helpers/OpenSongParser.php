<?php

namespace App\Helpers;

use Orchestra\Parser\Xml\Facade as XmlParser;

class OpenSongParser 
{
    protected $xmlParser;

    public function __construct($file_path)
    {
        $this->xmlParser = XmlParser::load($file_path);
    }

    public function getTitle()
    {
        return (string)$this->xmlParser->getConent()->title;
    }

    public function getLyrics()
    {
        $str = (string)$this->xmlParser->getContent()->lyrics;

        $match_verse = '/\[V([0-9])\]\n/';
        $match_single_verse = '/\[V\]\n/';
        $match_refr = '/\[C([0-9]?)\]\n/';
        $match_bridge = '/\[B([0-9]?)\]\n/';
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