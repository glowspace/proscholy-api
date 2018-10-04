<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Songbook
 *
 * @property int $id
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SongLyric[] $songLyrics
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Songbook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Songbook whereName($value)
 * @mixin \Eloquent
 */
class Songbook extends Model
{
    public function songLyrics()
    {
        return $this->belongsToMany(SongLyric::class, 'songbook_records');
    }
}
