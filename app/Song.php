<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * App\Song
 *
 * @property int                                                            $id
 * @property string|null                                                    $name
 * @property \Carbon\Carbon|null                                            $created_at
 * @property \Carbon\Carbon|null                                            $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[]    $authors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SongLyric[] $song_lyrics
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null                                                       $visits
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SongLyric[] $songLyrics
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Song whereCreatedAt($value)
 */
class Song extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function authors()
    {
        // TODO: return all authors of the SongLyrics combined ... but rather not necessary
        Log::error("get authors not implemented");
    }

    /**
     * Returns all SongLyrics instances
     */
    public function song_lyrics(): HasMany
    {
        return $this->hasMany(SongLyric::class);
    }

    public function translations()
    {
        return $this->song_lyrics()->where('type', '!=', 0);
    }

    public function getOriginalSongLyric()
    {
        return $this->song_lyrics()->where('type', 0)->get()->first();
    }
}
