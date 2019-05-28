<?php

namespace App\GraphQL\Types;

use App\Songbook;
use App\SongLyric;

class SongbookRecordType
{
    public function id($root)
    {
        return $root->pivot->id;
    }

    public function number($root)
    {
        return $root->pivot->number;
    }

    public function placeholder($root)
    {
        return $root->pivot->placeholder;
    }

    public function song_lyric($root) : SongLyric
    {
        return $root->pivot->song_lyric;
    }

    public function songbook($root) : Songbook
    {
        return $root->pivot->songbook;
    }
}