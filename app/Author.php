<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Log;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Venturecraft\Revisionable\RevisionableTrait;

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
class Author extends Model
{
    // Laravel Scout Trait used for full-text searching
    use Searchable, RevisionableTrait;
    protected $revisionCreationsEnabled = true;
    
    protected $fillable = ['name', 'description', 'email', 'url', 'type'];

    private $type_string_values
        = [
            0 => 'autor',
            1 => 'hudební uskupení',
            2 => 'schola',
            3 => 'kapela',
            4 => 'sbor',
        ];

    public function getSongLyricsInterpreted()
    {
        return SongLyric::whereHas('externals', function($q) {
            $q->media()->whereHas('authors', function($a) {
                $a->where('authors.id', $this->id);
            });
        })->orWhereHas('files', function($q) {
            $q->audio()->whereHas('authors', function($a) {
                $a->where('authors.id', $this->id);
            });
        });
    }

    public function getAssociatedAuthorsIds(){
        $authors = collect([$this]);

        return $authors->merge($this->members()->get())->map(function($a) {
            return $a["id"];
        })->toArray();
    }

    public function songLyricsWithAssociatedAuthors()
    {
        Log::info($this->getAssociatedAuthorsIds());

        $ids = $this->getAssociatedAuthorsIds();

        return SongLyric::whereHas('authors', function($q) use ($ids) {
            $q->whereIn('authors.id', $ids);
        });
    }

    public function scopeRestricted($query)
    {
        if (Auth::user()->hasRole('autor')) {
            return $query->whereIn('id', Auth::user()->getAssignedAuthorIds());
        } else {
            return $query;
        }
    }

    public function members() : BelongsToMany
    {
        return $this->belongsToMany(Author::class,
            'author_membership',
            'is_member_of',
            'author_id');
    }

    public function memberships() : BelongsToMany
    {
        return $this->belongsToMany(Author::class,
            'author_membership',
            'author_id',
            'is_member_of');
    }

    public function song_lyrics() : BelongsToMany
    {
        return $this->belongsToMany(SongLyric::class);
    }

    public function externals() : BelongsToMany
    {
        return $this->belongsToMany(External::class);
    }

    public function files() : BelongsToMany
    {
        return $this->belongsToMany(File::class);
    }

    public function getTypeStringAttribute()
    {
        return $this->type_string_values[$this->type];
    }

    public function getTypeStringValuesAttribute()
    {
        return $this->type_string_values;
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

    public function getPublicUrlAttribute()
    {
        return route('client.author', $this);
    }
}
