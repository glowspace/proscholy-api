<?php

namespace App\PublicModels;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\SongLyric;


class PlaylistSongLyric extends Pivot
{
    // protected $table = 'playlist_song_lyric';
    protected $fillable = ["order", "song_lyric_id", "songbook_id"];
    public $incrementing = true;
    
    public function song_lyric()
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
}
