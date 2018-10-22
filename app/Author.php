<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Author
 *
 * @property int                                                                  $id
 * @property string|null                                                          $name
 * @property string|null                                                          $url
 * @property string|null                                                          $ytchannel
 * @property string|null                                                          $description
 * @property string|null                                                          $email
 * @property string|null                                                          $password
 * @property int|null                                                             $type
 * @property \Carbon\Carbon|null                                                  $created_at
 * @property \Carbon\Carbon|null                                                  $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[]          $isMemberOf
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[]          $members
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SongLyric[] $songLyrics
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[]           $videos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereYtchannel($value)
 * @mixin \Eloquent
 * @property int|null $visits
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereVisits($value)
 */
class Author extends Model
{
    public function songLyrics()
    {
        return $this->belongsToMany(SongLyric::class);
    }

    public function members()   
    {
        return $this->belongsToMany(Author::class,
            'author_membership',
            'is_member_of',
            'author_id');
    }

    public function memberships()
    {
        return $this->belongsToMany(Author::class,
            'author_membership',
            'author_id',
            'is_member_of');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    // TODO
    public function getLink()
    {
        return '<a href="' . route('author.single', ['id' => $this->id]) . '">' . $this->name . '</a>';
    }
}
