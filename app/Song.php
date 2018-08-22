<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Song
 *
 * @property int                                                                  $id
 * @property string|null                                                          $name
 * @property string|null                                                          $licence_content
 * @property int|null                                                             $licence_type
 * @property int|null                                                             $visible
 * @property int|null                                                             $approved
 * @property \Carbon\Carbon|null                                                  $created_at
 * @property \Carbon\Carbon|null                                                  $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[]          $authors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SongTranslation[] $translations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereLicenceContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereLicenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereVisible($value)
 * @mixin \Eloquent
 * @property int|null $visits
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereVisits($value)
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
        $collection = $this->hasMany(SongTranslation::class);
        $collection->where('is_original', 0);

        return $collection;
    }


    public function getOriginalTranslation()
    {
        $count = $this->hasMany(SongTranslation::class)->where('is_original', 1)->count();
        if ($count > 0)
        {
            return $this->hasMany(SongTranslation::class)->where('is_original', 1)->first();
        }
        else
        {
            return null;
        }
    }

    public function getLink()
    {
        $link = '<a href="' . route('song.single', ['id' => $this->id]) . '">' . $this->name . '</a>';

        return $link;
    }
}
