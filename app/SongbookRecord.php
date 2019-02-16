<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
class SongbookRecord extends Model
{
    public function songLyric()
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function songbook()
    {
        return $this->belongsTo(Songbook::class);
    }

    public function generateTitle()
    {
        if (isset($this->song_lyric_id))
        {
            return $this->number . ': ' . $this->songLyric->name;
        }
        elseif (isset($this->placeholder))
        {
            return $this->number . ': ' . $this->placeholder;
        }
        else
        {
            return $this->songbook->name . ': ' . $this->number;
        }
    }
}
