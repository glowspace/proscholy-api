<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Video
 *
 * @property int                 $id
 * @property int|null            $author_id
 * @property int|null            $song_lyric_id
 * @property int|null            $type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null         $url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereSongLyricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUrl($value)
 * @mixin \Eloquent
 * @property int|null $visits
 * @property-read \App\Author|null $author
 * @property-read \App\SongLyric|null $songLyric
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereVisits($value)
 */
class Video extends Model
{
    public function getEmbedUrl()
    {
        return str_replace('watch?v=', 'embed/', $this->url);
    }

    public function getHtml()
    {
        $this->visits = $this->visits + 1;
        $this->save();

        return view('video_card', [
            'video' => $this,
        ]);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function songLyric()
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function generateTitle()
    {
        if (empty($this->author_id) || empty($this->song_lyric_id))
        {
            return "Video $this->id";
        }
        else
        {
            return $this->author->name . ' - ' . $this->songLyric->name;
        }

    }
}
