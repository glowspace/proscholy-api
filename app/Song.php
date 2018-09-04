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
        // TODO: return all authors of the SongLyrics combined ... but rather not necessary
        Log::error("get authors not implemented");
    }

    /**
     * Returns all SongLyrics instances
     */
    public function song_lyrics()
    {
        return $this->hasMany(SongLyric::class);
    }

    public function getOriginalSongLyricAttribute()
    {
        return $this->song_lyrics()->where('is_original', 1)->get()->first();
    }


    // // public function getOriginalTranslation()
    // // {
    // //     $count = $this->hasMany(SongLyric::class)->where('is_original', 1)->count();
    // //     if ($count > 0)
    // //     {
    // //         return $this->hasMany(SongLyric::class)->where('is_original', 1)->first();
    // //     }
    // //     else
    // //     {
    // //         return null;
    // //     }
    // // }

    // TODO: return only plain link, not an html element
    public function getLink()
    {
        $link = '<a href="' . route('song.single', ['id' => $this->id]) . '">' . $this->name . '</a>';

        return $link;
    }
}
