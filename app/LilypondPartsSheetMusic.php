<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LilypondPartsSheetMusic extends Model
{
    use HasFactory;

    protected $fillable = ['lilypond_parts', 'settings'];

    public function song_lyric(): BelongsTo
    {
        return $this->belongsTo(SongLyric::class);
    }
}
