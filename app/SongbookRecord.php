<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\SongbookRecord
 *
 * @property int $id
 * @property int|null $songbook_id
 * @property int|null $song_lyric_id
 * @property string|null $number
 * @property string|null $placeholder
 * @property-read \App\SongLyric|null $songLyric
 * @property-read \App\Songbook|null $songbook
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord wherePlaceholder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereSongLyricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereSongbookId($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereUpdatedAt($value)
 */
class SongbookRecord extends Pivot
{
    protected $table = 'songbook_records';
    protected $fillable = ["placeholder", "number", "song_lyric_id", "songbook_id"];
    public $incrementing = true;
    // automatically updates updated_at for song_lyric when the records are changed
    protected $touches = ['song_lyric'];

    public function song_lyric(): BelongsTo
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function songbook(): BelongsTo
    {
        return $this->belongsTo(Songbook::class);
    }
}
