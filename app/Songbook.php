<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Lockable;

/**
 * App\Songbook
 *
 * @property int $id
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SongLyric[] $songLyrics
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Songbook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Songbook whereName($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Songbook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Songbook whereUpdatedAt($value)
 */
class Songbook extends Model
{
    use Lockable;

    protected $fillable = ['name', 'shortcut', 'songs_count', 'is_private', 'color', 'color_text', 'songbook_img_url'];

    public function records(): BelongsToMany
    {
        return $this->belongsToMany(SongLyric::class, "songbook_records")
            ->withPivot('number', 'placeholder', 'id')
            ->using(SongbookRecord::class);
    }

    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    public function scopeCez($query)
    {
        return $query->where('name', 'ČEZ');
    }
}
