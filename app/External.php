<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

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
    protected $fillable = ['url', 'type', 'is_featured', 'has_anonymous_author'];

    public $type_string
        = [
            0 => 'odkaz',
            1 => 'spotify URI',
            2 => 'soundcloud',
            3 => 'youtube',
            4 => 'noty',
            5 => 'webová stránka autora',
            6 => 'youtube kanál'
        ];

    /**
     * @return string
     */
    public function getTypeString()
    {
        return $this->type_string[$this->type];
    }

    public function scopeScores($query)
    {
        return $query->where('type', 4);
    }

    public function scopeAudio($query)
    {
        return $query->where('type', 1)->orWhere('type', 2);
    }

    public function scopeMedia($query)
    {
        return $query->where('type', 1)->orWhere('type', 2)->orWhere('type', 3);
    }

    public static function urlAsSpotify($url)
    {
        // spotify:track:3X7QBr7rq6NIzLmEXbiXAS
        $uri_prefix = "spotify:track:";
        // https://open.spotify.com/track/2nwCO1PqpvyoFIvq3Vrj8N?si=kpz8FS1zSYG7dKv12kU1kA
        $link_prefix = preg_quote("https://open.spotify.com/track/", "/");

        // check if it's a valid Spotify URI or link
        if (!preg_match("/($uri_prefix|$link_prefix)([^\?]+)/", $url, $groups)) {
            return false;
        }

        return $groups[2];

        // return "https://open.spotify.com/embed/track/$groups[2]";
    }

    public static function urlAsYoutube($url)
    {
        $short = preg_quote("https://youtu.be/", '/');
        $long = preg_quote("https://www.youtube.com/watch?v=", '/');
        
        if (!preg_match("/($short|$long)(.+)/", $url, $groups)) {
            return false;
        }

        return $groups[2];

        // return "https://www.youtube.com/embed/$groups[2]";
    }

    public static function urlAsSoundcloud($url)
    {
        $prefix = preg_quote("https://soundcloud.com/", "/");
        if (!preg_match("/$prefix(.+)/", $url)) {
            return false;
        }

        return $url;

        // return "https://w.soundcloud.com/player/?url=$this->url
        //     &color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true";
    }

    // helper getter
    public function getMediaIdAttribute()
    {
        if ($this->type == 1) return self::urlAsSpotify($this->url);
        if ($this->type == 2) return self::urlAsSoundcloud($this->url);
        if ($this->type == 3) return self::urlAsYoutube($this->url);

        return false;
    }

    public function guessType()
    {
        if ($this->urlAsSpotify($this->url)) return 1;
        if ($this->urlAsSoundcloud($this->url)) return 2;
        if ($this->urlAsYoutube($this->url)) return 3;

        return 0;
    }

    protected static function getThubmnailsFolder()
    {
        $relative = '/public_files/thumbnails_externals';

        // first create if doesn't exist
        if (!file_exists(Storage::path($relative)))
            mkdir(Storage::path($relative));

        return $relative;
    }

    public function canHaveThumbnail()
    {
        return pathinfo($this->url, PATHINFO_EXTENSION) == "pdf";
    }

    public function getThumbnailPath()
    {
        if (!$this->canHaveThumbnail())
            return;

        // get the path of a thumbnail file
        $relative = self::getThubmnailsFolder()."/$this->id.jpg";

        // if already exists, do not create new one
        if (file_exists(Storage::path($relative))) {
            return $relative;
        }
        
        // create a new thumbnail file
        $pdf = new Pdf($this->url);
        $pdf->setPage(1)
            ->setCompressionQuality(60)
            ->saveImage(Storage::path($relative));

        \Log::info("thumbnail $relative created");

        return $relative;
    }

    public function getThumbnailUrlAttribute()
    {
        return route('external.thumbnail', [
            'external' => $this->id,
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

    public function scopeRestricted($query)
    {
        if (Auth::user()->hasRole('autor')) {
            return $query->whereHas('author', function($q) {
                $q->whereIn('authors.id', Auth::user()->getAssignedAuthorIds());
            })->orWhereHas('song_lyric', function($q) {
                $q->restricted();
            });
        } else {
            return $query;
        }
    }

    public function generateTitle()
    {
        // TODO better condition
        if (empty($this->author_id) || empty($this->song_lyric_id))
        {
            return "Typ: " . $this->type_string[$this->type] . " č. $this->id";
        }
        else
        {
            return $this->author->name . ' - ' . $this->song_lyric->name . " (" . $this->type_string[$this->type] . ")";
        }
    }
}
