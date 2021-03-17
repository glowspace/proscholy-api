<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongLyricLilypondSvg extends Model
{
    protected $table = 'song_lyric_lilypond_svg';
    protected $fillable = ["song_lyric_id", "lilypond_svg"];
    public $incrementing = true;
    public $timestamps = false;

    public function song_lyric()
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function __toString()
    {
        return $this->lilypond_svg;
    }
}
