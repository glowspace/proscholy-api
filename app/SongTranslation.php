<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SongTranslation
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $is_authorized
 * @property int|null $is_original
 * @property string|null $description
 * @property string|null $lyrics
 * @property int|null $is_opensong
 * @property int|null $lang_id
 * @property int|null $song_id
 * @property int|null $licence_type
 * @property string|null $licence_content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[] $authors
 * @property-read \App\Song|null $song
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereIsAuthorized($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereIsOpensong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereIsOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereLangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereLicenceContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereLicenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereLyrics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SongTranslation extends Model
{
    public function song(){
        return $this->belongsTo(Song::class);
    }

    public function authors(){
        return $this->belongsToMany(Author::class);
    }

    public function getLink()
    {
        if($this->is_original == 1)
        {
            $link = '<a href="'.route('song.single',['id'=>$this->song->id]).'">' . $this->name . '</a>';
        }
        else {
            $link = '<a href="'.route('translation.single',['id'=>$this->id]).'">' . $this->name . '</a>';
        }

        return $link;
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
