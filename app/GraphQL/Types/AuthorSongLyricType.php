<?php

namespace App\GraphQL\Types;

use App\Author;
use App\SongLyric;

class AuthorSongLyricType
{
    public function id($root)
    {
        return $root->pivot->id;
    }

    public function authorship_type($root)
    {
        return $root->pivot->authorship_type;
    }

    public function song_lyric($root): SongLyric
    {
        return $root->pivot->song_lyric;
    }

    public function author($root): Author
    {
        return $root->pivot->author;
    }
}
