<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * App\External
 *
 * @property int                      $id
 * @property int|null                 $author_id
 * @property int|null                 $song_lyric_id
 * @property int|null                 $type
 * @property \Carbon\Carbon|null      $created_at
 * @property \Carbon\Carbon|null      $updated_at
 * @property string|null              $url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereSongLyricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereUrl($value)
 * @mixin \Eloquent
 * @property int|null                 $visits
 * @property-read \App\Author|null    $author
 * @property-read \App\SongLyric|null $songLyric
 * @method static \Illuminate\Database\Eloquent\Builder|\App\External whereVisits($value)
 */
class External extends Model
{
    protected $fillable = ['url', 'type'];

    public $type_string
        = [
            0 => 'link',
            1 => 'spotify',
            2 => 'soundcloud',
            3 => 'youtube',
            4 => 'score_link',
        ];

    /**
     * @return string
     */
    public function getTypeString()
    {
        return $this->type_string[$this->type];
    }

    /**
     * @throws Exception
     */
    public function getEmbedUrl()
    {
        if ($this->getTypeString() == 'youtube')
        {
            return str_replace('watch?v=', 'embed/', $this->url);
        }
        else
        {
            return $this->url;
        }
    }

    public function getHtml()
    {
        $this->visits = $this->visits + 1;
        $this->save();

        return view('client.components.external_embed', [
            'external' => $this,
        ]);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function song_lyric()
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function generateTitle()
    {
        // TODO better condition
        if (empty($this->author_id) || empty($this->song_lyric_id))
        {
            return "Video $this->id";
        }
        else
        {
            return $this->author->name . ' - ' . $this->song_lyric->name;
        }

    }
}
