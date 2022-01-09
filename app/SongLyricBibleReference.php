<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SongLyricBibleReference extends Model
{
    // protected $table = 'song_lyric_bible_reference';
    // protected $fillable = ["song_lyric_id", "lyrics"];
    public $incrementing = true;
    public $timestamps = false;

    public function song_lyric()
    {
        return $this->belongsTo(SongLyric::class);
    }
}
