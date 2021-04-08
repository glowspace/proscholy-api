<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;
use Hash;
use App\Interfaces\ISource;

use Illuminate\Support\Arr;

use App\Helpers\ExternalMediaLink;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\External
 *
 * @property int                      $id
 * @property int|null                 $author_id
 * @property int|null                 $song_lyric_id
 * @property int                     $type
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
    use RevisionableTrait, HasFactory;
    protected $revisionCleanup = true;
    protected $historyLimit = 200;
    protected $revisionCreationsEnabled = true;
    protected $dontKeepRevisionOf = ['is_uploaded', 'media_type', 'content_type'];


    protected $fillable = ['url', 'is_featured', 'has_anonymous_author', 'catalog_number', 'copyright', 'editor', 'published_by', 'is_uploaded', 'caption', 'media_type', 'content_type'];

    private $type_string_values
    = [
        0 => 'odkaz',
        1 => 'spotify URI',
        2 => 'soundcloud',
        3 => 'youtube',
        4 => 'noty',
        5 => 'webová stránka autora',
        6 => 'youtube kanál',
        7 => 'audio soubor',
        8 => 'text s akordy (pdf)',
        9 => 'text (pdf)',
        10 => 'facebook',
        11 => 'instagram',
        12 => 'profilová fotka',
        13 => 'fotka',
    ];

    private $media_type_string_values
    = [
        'spotify',
        'soundcloud',
        'youtube',
        'file/mp3',
        'file/wav',
        'file/aac',
        'file/flac',
        'file/pdf',
        'file/jpeg',
        'file/png',
        'file/gif',
        'file/doc',
        'file/docx'
    ];

    private $content_type_string_values
    = [
        'UNDEFINED' => 'neurčeno',
        'RECORDING' => 'nahrávka',
        'WEBSITE' => 'externí webová stránka',
        'SCORE' => 'noty',
        'LYRICS' => 'text',
        // 'LICENSE' => 'licence'
        // 'PHOTO' => 'fotka' // this should not be available for generic Externals (only for specific-purpose stuff)
        // 'SOCIAL' => 'profil na sociální síti' // this should not be available for generic Externals (only for specific-purpose stuff)
    ];

    protected $dispatchesEvents = [
        'created' => \App\Events\ExternalCreated::class,
        'deleting' => \App\Events\ExternalDeleting::class,
    ];

    // todo: deprecate
    public function getTypeStringAttribute()
    {
        return $this->type_string_values[$this->type];
    }

    // todo: deprecate
    public function getTypeStringValuesAttribute()
    {
        return $this->type_string_values;
    }

    public function getMediaTypeValuesAttribute()
    {
        return $this->media_type_string_values;
    }

    public function getContentTypeStringAttribute()
    {
        $graphql_enum_table = [
            0 => 'UNDEFINED',
            1 => 'RECORDING',
            2 => 'SCORE',
            3 => 'LYRICS',
            4 => 'WEBSITE',
            5 => 'LICENSE'
        ];

        return $this->content_type_string_values[$graphql_enum_table[$this->content_type]];
    }

    public function getContentTypeStringValuesAttribute()
    {
        return $this->content_type_string_values;
    }

    public function scopeScores($query)
    {
        return $query->where('content_type', 2);
    }

    // todo: deprecate
    public function scopeAudio($query)
    {
        return $query->media()->where('media_type', '!=', 'youtube');
    }

    public function scopeMedia($query)
    {
        return $query->where('content_type', 1);
    }

    public function scopeTodo($query)
    {
        return $query->where(function ($query) {
            $query->doesntHave('authors')->where('has_anonymous_author', 0);
        })->orDoesntHave('song_lyric');
    }

    // todo: check
    public function getMediaIdAttribute()
    {
        if (!$this->url) {
            return;
        }

        $media_link = new ExternalMediaLink($this->url);

        if ($this->media_type == 'spotify') return $media_link->urlAsSpotify();
        if ($this->media_type == 'soundcloud') return $media_link->urlAsSoundcloud();
        if ($this->media_type == 'youtube') return $media_link->urlAsYoutube();

        if ($this->is_uploaded) {
            return str_replace('public_files/', '', $this->filepath);
        }

        return;
    }

    // protected static function getThubmnailsFolder()
    // {
    //     $relative = '/public_files/thumbnails_externals';

    //     // first create if doesn't exist
    //     if (!file_exists(Storage::path($relative)))
    //         mkdir(Storage::path($relative));

    //     return $relative;
    // }

    // public function canHaveThumbnail()
    // {
    //     return pathinfo($this->url, PATHINFO_EXTENSION) == "pdf";
    // }

    // public function getThumbnailPath()
    // {
    //     if (!$this->canHaveThumbnail())
    //         return;

    //     // generate unique filename from the url
    //     $hash_name = md5($this->url);

    //     // get the path of a thumbnail file
    //     $relative = self::getThubmnailsFolder()."/$hash_name.jpg";

    //     // if already exists, do not create new one
    //     if (file_exists(Storage::path($relative))) {
    //         return $relative;
    //     }

    //     // create a new thumbnail file
    //     $pdf = new Pdf($this->url);
    //     $pdf->setCompressionQuality(20)
    //         ->saveImage(Storage::path($relative));

    //     \Log::info("thumbnail $relative created");

    //     return $relative;
    // }

    // public function getThumbnailUrlAttribute()
    // {
    //     if (!$this->canHaveThumbnail())
    //         return;

    //     return route('external.thumbnail', [
    //         'external' => $this->id,
    //     ]);
    // }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function song_lyric(): BelongsTo
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function rendered_scores(): HasMany
    {
        return $this->hasMany(RenderedScore::class);
    }

    public function scopeRestricted($query)
    {
        if (Auth::user()->hasRole('autor')) {
            return $query->whereHas('author', function ($q) {
                $q->whereIn('authors.id', Auth::user()->getAssignedAuthorIds());
            })->orWhereHas('song_lyric', function ($q) {
                $q->restricted();
            });
        } else {
            return $query;
        }
    }

    // todo: refactor to use new types (and caption)
    public function getPublicNameAttribute()
    {
        $type = $this->type_string_values[$this->type];

        $info = "";

        if ($this->song_lyric) {
            $info .= $this->song_lyric->name;
        }

        if ($this->song_lyric && $this->authors->count() > 0) {
            $info .= " | ";
        }

        $info .= $this->authors->implode('name', ', ');

        if ($info !== "") {
            $info = "($info)";
        } else {
            $info = "#$this->id";
        }

        return "$type $info";
    }

    public function getFilepathAttribute()
    {
        // only available when External `is_uploaded`
        if (!$this->is_uploaded)
            return null;

        $path = str_replace(url('') . '/', "", $this->url);
        $path = str_replace('soubor', 'public_files', $path);

        return $path;
    }

    // legacy method for a deprecated attribute
    public function getTypeAttribute()
    {
        $arr = [
            'spotify' => 1,
            'soundcloud' => 2,
            'youtube' => 3,
            'file/mp3' => 7,
            'file/pdf' => 4,
            'file/jpeg' => 4
        ];

        if (array_key_exists($this->media_type, $arr)) {
            return $arr[$this->media_type];
        }

        return 0;
    }

    public function getMimeTypeAttribute()
    {
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
        $table = [
            'file/mp3' => 'audio/mpeg',
            'file/wav' => 'audio/wav',
            'file/aac' => 'audio/aac',
            'file/flac' => 'audio/flac',
            'file/pdf' => 'application/pdf',
            'file/jpeg' => 'image/jpeg',
            'file/png' => 'image/png',
            'file/gif' => 'image/gif',
            'file/doc' => 'application/msword',
            'file/docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (Arr::has($table, $this->media_type)) {
            return $table[$this->media_type];
        }

        return "";
    }
}
