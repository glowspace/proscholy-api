<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiturgicalReference extends Model
{
    protected $fillable = ['type', 'date', 'cycle', 'reading', 'song_lyric_id'];

    public function song_lyric(): BelongsTo
    {
        return $this->belongsTo(SongLyric::class);
    }
}
