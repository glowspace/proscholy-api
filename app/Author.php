<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * App\Author
 *
 * @property int                                                            $id
 * @property string|null                                                    $name
 * @property string|null                                                    $url
 * @property string|null                                                    $ytchannel
 * @property string|null                                                    $description
 * @property string|null                                                    $email
 * @property string|null                                                    $password
 * @property int|null                                                       $type
 * @property \Carbon\Carbon|null                                            $created_at
 * @property \Carbon\Carbon|null                                            $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[]    $isMemberOf
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[]    $members
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SongLyric[] $songLyrics
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\External[]  $externals
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
 * @property int|null                                                       $visits
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereVisits($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[]    $memberships
 */
class Author extends Model implements ISearchResult
{
    // Laravel Scout Trait used for full-text searching
    use Searchable;
    protected $fillable = ['name', 'description', 'email', 'url', 'type'];

    public $type_string
        = [
            0 => 'autor',
            1 => 'hudební uskupení',
            2 => 'schola',
            3 => 'kapela',
            4 => 'sbor',
        ];


    public function songLyrics()
    {
        return $this->belongsToMany(SongLyric::class);
    }

    public function getSongLyricsInterpreted()
    {
        return SongLyric::whereHas('externals', function($q) {
            // $q->whereIn('externals.id', );
            $q->media()->where('author_id', $this->id);
        })->orWhereHas('files', function($q) {
            $q->audio()->where('author_id', $this->id);
        });
    }

    public function songOriginalLyrics()
    {
        return $this->songLyrics()->where('is_original', true);
    }

    public function songNotOriginalLyrics()
    {
        return $this->songLyrics()->where('is_original', false);
    }

    // 
    public function scopeRestricted($query)
    {
        if (Auth::user()->hasRole('autor')) {
            return $query->whereIn('id', Auth::user()->getAssignedAuthorIds());
        } else {
            return $query;
        }
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

    public function externals()
    {
        return $this->hasMany(External::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    // TODO
    public function getLink()
    {
        return '<a href="' . route('client.author', $this) . '">' . $this->name . '</a>';
    }

    public function getTypeText()
    {
        return $this->type_string[$this->type];
    }

    public function getTypeStringAttribute()
    {
        return $this->getTypeText();
    }

    public static function getByIdOrCreateWithName($identificator, $uniqueName = false)
    {
        if (is_numeric($identificator))
        {
            return Author::find($identificator);
        }
        else
        {
            $double = Author::where('name', $identificator)->first();
            if ($uniqueName && $double != null) {
                return $double;
            }

            $author = Author::create([
                'name' => $identificator,
            ]);

            return $author;
        }
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array;
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Preserve only attributes that are meant to be searched in
        $searchable = Arr::only($array, ['name', 'description']);

        return $searchable;
    }

    // implementing INTERFACE ISearchResult

    public function getSearchTitle()
    {
        return $this->name;
    }

    public function getSearchText()
    {
        return $this->type_string[$this->type];
    }

    public function getPublicUrlAttribute()
    {
        return route('client.author', $this);
    }
}
