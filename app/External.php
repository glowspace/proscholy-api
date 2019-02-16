<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * App\External
 *
 * @property int                 $id
 * @property int|null            $author_id
 * @property int|null            $song_lyric_id
 * @property int|null            $type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null         $url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereSongLyricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereUrl($value)
 * @mixin \Eloquent
 * @property int|null $visits
 * @property-read \App\Author|null $author
 * @property-read \App\SongLyric|null $songLyric
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereVisits($value)
 */
class External extends Model
{
    public function getEmbedUrl()
    {
        throw new Exception("unimplemented");
        // return str_replace('watch?v=', 'embed/', $this->url);
    }

    public function getHtml()
    {
        $this->visits = $this->visits + 1;
        $this->save();

        return view('external_card', [
            'external' => $this,
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
            return "External $this->id";
        }
        else
        {
            return $this->author->name . ' - ' . $this->songLyric->name;
        }

    }
}
