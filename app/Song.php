<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Song
 *
 * @property int                                                                  $id
 * @property string|null                                                          $name
 * @property \Carbon\Carbon|null                                                  $created_at
 * @property \Carbon\Carbon|null                                                  $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[]          $authors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SongLyric[]       $song_lyrics
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $visits
 */
class Song extends Model
{
    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    /**
     * Vrátí všechny překlady kromě originálu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        $collection = $this->hasMany(SongLyric::class);
        $collection->where('is_original', 0);

        return $collection;
    }


    public function getOriginalTranslation()
    {
        $count = $this->hasMany(SongLyric::class)->where('is_original', 1)->count();
        if ($count > 0)
        {
            return $this->hasMany(SongLyric::class)->where('is_original', 1)->first();
        }
        else
        {
            return null;
        }
    }

    // TODO: return only plain link, not an html element
    public function getLink()
    {
        $link = '<a href="' . route('song.single', ['id' => $this->id]) . '">' . $this->name . '</a>';

        return $link;
    }
}
