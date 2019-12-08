<?php

namespace App\PublicModels;

use Illuminate\Database\Eloquent\Model;

use App\SongLyric;

class Playlist extends Model
{
    protected $fillable = [
        'name', 'is_private'
    ];

    public function song_lyrics() {
        return $this->belongsToMany(SongLyric::class);
    }
}
