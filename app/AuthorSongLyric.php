<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AuthorSongLyric extends Pivot
{
    protected $table = 'author_song_lyric';
    protected $fillable = ["id", "author_id", "song_lyric_id", "authorship_type"];
    public $incrementing = true;

    public static $authorship_type_string_values = [
        'GENERIC' => 'Obecný',
        'LYRICS' => 'Text',
        'MUSIC' => 'Hudba',
    ];

    public function song_lyric(): BelongsTo
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
