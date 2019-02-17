<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Support\Arr;

/**
 * App\SongLyric
 *
 * @property int                                                           $id
 * @property string|null                                                   $name
 * @property int|null                                                      $is_authorized
 * @property int|null                                                      $is_original
 * @property string|null                                                   $description
 * @property string|null                                                   $lyrics
 * @property int|null                                                      $is_opensong
 * @property int|null                                                      $lang_id
 * @property int|null                                                      $song_id
 * @property int|null                                                      $licence_type
 * @property string|null                                                   $licence_content
 * @property \Carbon\Carbon|null                                           $created_at
 * @property \Carbon\Carbon|null                                           $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[]   $authors
 * @property-read \App\Song|null                                           $song
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereIsAuthorized($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereIsOpensong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereIsOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereLangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereLicenceContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereLicenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereLyrics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null                                                      $visits
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereVisits($value)
 * @property string                                                        $lang
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\External[] $externals
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereLang($value)
 */
class SongLyric extends Model implements ISearchResult
{
    // Laravel Scout Trait used for full-text searching
    use Searchable;

    protected $fillable = ['name', 'song_id', 'lyrics', 'id', 'is_original', 'is_authorized'];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function getLink()
    {
        return route('client.song.text', ['id' => $this->id]);
    }

    public function externals()
    {
        return $this->hasMany(External::class);
    }

    public function youtubeVideos()
    {
        return $this->externals()->where('type', 0);
    }

    public function spotifyTracks()
    {
        return $this->externals()->where('type', 1);
    }

    public function soundcloudTracks()
    {
        return $this->externals()->where('type', 2);
    }

    public function audioTracks()
    {
        return $this->spotifyTracks->concat($this->soundcloudTracks);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Preserve only attributes that are meant to be searched in
        $searchable = Arr::only($array, ['name', 'lyrics', 'description']);

        return $searchable;
    }

    // implementing INTERFACE ISearchResult

    public function getSearchTitle()
    {
        return $this->name;
    }

    public function getSearchText()
    {
        return $this->lyrics;
    }
}
