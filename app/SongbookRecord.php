<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SongbookRecord
 *
 * @property int $id
 * @property int|null $songbook_id
 * @property int|null $song_translation_id
 * @property string|null $number
 * @property string|null $placeholder
 * @property-read \App\SongTranslation|null $songTranslation
 * @property-read \App\Songbook|null $songbook
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord wherePlaceholder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereSongTranslationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongbookRecord whereSongbookId($value)
 * @mixin \Eloquent
 */
class SongbookRecord extends Model
{
    public function songTranslation()
    {
        return $this->belongsTo(SongTranslation::class);
    }

    public function songbook()
    {
        return $this->belongsTo(Songbook::class);
    }

    public function generateTitle()
    {
        if (isset($this->song_translation_id))
        {
            return $this->number . ': ' . $this->songTranslation->name;
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
