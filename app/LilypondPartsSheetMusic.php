<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Support\Str;

class LilypondPartsSheetMusic extends Model
{
    use HasFactory;

    protected $fillable = ['lilypond_parts', 'score_config', 'global_src', 'is_empty', 'sequence_string'];

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

    public function getRenderableAttribute()
    {
        return !$this->is_empty;
    }

    public function getPartsSrc()
    {
        $src = '';

        foreach ($this->lilypond_parts as $part) {
            if (isset($part['src'])) {
                $src .= $part['src'];
            }
        }

        return $src;
    }

    public function hasAnyVoice($voicenames)
    {
        return Str::contains($this->getPartsSrc(), $voicenames);
    }
}
