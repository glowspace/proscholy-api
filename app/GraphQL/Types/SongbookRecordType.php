<?php

namespace App\GraphQL\Types;

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

    public function song_lyric($root)
    {
        return $root->pivot->song_lyric;
    }
}