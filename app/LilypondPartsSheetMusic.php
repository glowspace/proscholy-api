<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LilypondPartsSheetMusic extends Model
{
    use HasFactory;

    protected $fillable = ['lilypond_parts', 'score_config', 'global_src', 'is_empty'];

    protected $casts = [
        'lilypond_parts' => 'array',
        'score_config' => 'array',
        'is_empty' => 'boolean'
    ];

    public function song_lyric(): BelongsTo
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function rendered_scores(): HasMany
    {
        return $this->hasMany(RenderedScore::class, 'lilypond_parts_sheet_music_id');
    }

    public function scopeRenderable($query)
    {
        return $query->where('is_empty', false);
    }

    public function getSrcIsEmpty()
    {
        $src = '';

        logger($this->lilypond_parts);

        foreach ($this->lilypond_parts as $part) {
            if (isset($part['src'])) {
                $src .= $part['src'];
            }
        }

        return empty(trim($src));
    }
}
