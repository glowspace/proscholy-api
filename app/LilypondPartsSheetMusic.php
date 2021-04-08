<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LilypondPartsSheetMusic extends Model
{
    use HasFactory;

    protected $fillable = ['lilypond_parts', 'global_config', 'global_src'];

    protected $casts = [
        'lilypond_parts' => 'array',
        'global_config' => 'array'
    ];

    public function song_lyric(): BelongsTo
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function getIsEmptyAttribute()
    {
        $src = '';

        foreach ($this->lilypond_parts as $part) {
            if (isset($part['src'])) {
                $src .= $part['src'];
            }
        }

        return empty(trim($src));
    }
}
