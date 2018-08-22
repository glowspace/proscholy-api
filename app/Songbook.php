<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Songbook
 *
 * @property int $id
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SongTranslation[] $songTranslations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Songbook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Songbook whereName($value)
 * @mixin \Eloquent
 */
class Songbook extends Model
{
    public function songTranslations()
    {
        return $this->belongsToMany(SongTranslation::class, 'songbook_records');
    }
}
