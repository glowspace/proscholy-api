<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SongLyricLyrics extends Model
{
    protected $table = 'song_lyric_lyrics';
    protected $fillable = ["song_lyric_id", "lyrics"];
    public $incrementing = true;
    public $timestamps = false;

    public function song_lyric()
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function __toString()
    {
        return $this->lyrics;
    }
}
