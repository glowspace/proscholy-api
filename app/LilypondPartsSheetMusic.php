<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function rendered_scores(): HasMany
    {
        return $this->hasMany(RenderedScore::class, 'lilypond_parts_sheet_music_id');
    }
}
