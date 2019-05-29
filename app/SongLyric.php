<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Traits\Lockable;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Helpers\Chord;
use App\Helpers\ChordSign;
use App\Helpers\ChordQueue;

/**
 * App\SongLyric
 *
 * @property int                                                           $id
 * @property string|null                                                   $name
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
class SongLyric extends Model
{
    // Laravel Scout Trait used for full-text searching
    // Lockable Trait for enabling to "lock" the model while editing
    use Searchable, Lockable, SoftDeletes;

    protected $dispatchesEvents = [
        'saved' => \App\Events\SongLyricSaved::class,
        'updated' => \App\Events\SongLyricSaved::class,
        'created' => \App\Events\SongLyricCreated::class,
    ];

    protected $fillable
        = [
            'name',
            'song_id',
            'lyrics',
            'id',
            // 'is_original',
            // 'is_authorized',
            'type',
            'lang',
            'creating_at',
            'has_anonymous_author',
            'has_chords',
            'is_published',
            'is_approved_by_author',
            'user_creator_id',
            'licence_type'
        ];

    private static $lang_string_values = [
        'cs' => 'čeština',
        'sk' => 'slovenština',
        'en' => 'angličtina',
        'la' => 'latina',
        'pl' => 'polština',
        'de' => 'němčina',
        'fr' => 'francouzština',
        'es' => 'španělština',
        'it' => 'italština',
        'sv' => 'svahilština',
        'he' => 'hebrejština',
        'cu' => 'staroslověnština',
        // 'wtf' => 'jazyk domorodých kmenů jižní Oceánie',
        'mixed' => 'vícejazyčná píseň'
    ];

    public function getPublicUrlAttribute()
    {
        return route('client.song.text', [
            'song_lyric' => $this,
            'name' => str_slug($this->name)
        ]);
    }

    public function getLyricsNoChordsAttribute()
    {
        $str = preg_replace(
            array('/-/', '/\[[^\]]+\]/'),
            array("", ""),
            $this->lyrics
        );

        return $str;
    }

    // TODO: implement
    public function getIsEmptyAttribute()
    {
        // return $this->lyrics == null 
        //     && $this->externals()->count() +
        //     $this->files()->count() == 0
        //     && 
    }


    // ! deprecated soon
    public function getIsOriginalAttribute()
    {
        return $this->type == 0;
    }

    // ! deprecated soon
    public function getIsAuthorizedAttribute()
    {
        return $this->type == 2;
    }

    public function getLangStringAttribute()
    {
        return self::$lang_string_values[$this->lang];
    }

    public function getLangStringValuesAttribute()
    {
        return self::$lang_string_values;
    }

    public function song() : BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    public function authors() : BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function externals() : HasMany
    {
        return $this->hasMany(External::class);
    }

    public function files() : HasMany
    {
        return $this->hasMany(File::class);
    }

    public function songbook_records() : BelongsToMany
    {
        return $this->belongsToMany(Songbook::class, "songbook_records")
                    ->withPivot('number', 'placeholder', 'id')
                    ->using(SongbookRecord::class);
    }

    public function scopeTranslations($query)
    {
        return $query->where('type', '!=', 0);
    }

    public function scopeOriginals($query)
    {
        return $query->where('type', 0);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function scopeRestricted($query)
    {
        // restrict results if current user is Author
        if (Auth::user()->hasRole('autor')) {
            return $query->forceRestricted();
        } else {
            return $query;
        }
    }

    public function scopeForceRestricted($query)
    {
        // show songs, where there is at least one common author 
        // of song authors and to-user-assigned authors
        return $query->whereHas('authors', function($q) {
            $q->whereIn('authors.id', Auth::user()->getAssignedAuthorIds());
        // and show those songs, that were created by this user account
        })->orWhere('user_creator_id', Auth::user()->id);
    }

    public function scopeNotEmpty($query)
    {
        // return SongLyrycs that have at least one of:
        // lyrics, sheet music

        return $query->whereHas('scoreExternals')
                    ->orWhereHas('scoreFiles')
                    ->orWhere('lyrics', '!=', '');
    }

    /*
     * Real type collections
     */
    public function spotifyTracks()
    {
        return $this->externals()->where('type', 1)->orderBy('is_featured', 'desc');
    }

    public function soundcloudTracks()
    {
        return $this->externals()->where('type', 2)->orderBy('is_featured', 'desc');
    }

    public function youtubeVideos()
    {
        return $this->externals()->where('type', 3)->orderBy('is_featured', 'desc');
    }

    public function audioFiles()
    {
        return $this->files()->audio();
    }

    public function scoreExternals()
    {
        return $this->externals()->scores()->orderBy('is_featured', 'desc')->orderBy('type', 'asc');
    }
    
    public function scoreFiles()
    {
        return $this->files()->scores()->orderBy('type', 'desc');
    }
    

    public function scoresCount()
    {
        return $this->scoreExternals()->count() + $this->scoreFiles()->count();
    }

    // the reason for existence of the domestic characteristic
    // is the case when there are multiple SongLyrics under one Song and no original one
    // which is permitted when the original is unknown
    // TODO: make obsolete
    public function isDomestic()
    {
        return $this->name === $this->song->name;
    }


    public function getSiblings()
    {
        return $this->song->song_lyrics()->where('id', '!=', $this->id)->get();
    }

    public function hasSiblings()
    {
        return $this->getSiblings()->count() > 0;
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
        $searchable = Arr::only($array, ['name', 'lyrics']);

        return $searchable;
    }

    public function getFormattedLyrics()
    {
        $lines = explode("\n", $this->lyrics);

        $output = "";
        $chordQueue = new ChordQueue();

        foreach ($lines as $line){
            $output .= '<div class="song-line">'.$this->processLine($line, $chordQueue).'</div>';
        }

        return $output;
    }

    private function processLine($line, $chordQueue)
    {
        $chords = array();
        $currentChordText = "";
        $line = trim($line);
        
        // starting of a line, notify Chord "repeater" if we are in a verse
        if (strlen($line) > 0 && is_numeric($line[0])) {
            $chordQueue->notifyVerse($line[0]);
        }

        for ($i = 0; $i < strlen($line); $i++) {
            if ($line[$i] == "["){
                if ($currentChordText != "")
                    $chords[] = Chord::parseFromText($currentChordText, $chordQueue);
                $currentChordText = "";
            }

            $currentChordText .= $line[$i];
        }

        $chords[] = Chord::parseFromText($currentChordText, $chordQueue);

        $string = "";
        foreach ($chords as $chord) 
            $string .= $chord->toHTML();

        return $string;
    }

    // todo: make obsolete
    public static function getByIdOrCreateWithName($identificator, $uniqueName = false)
    {
        if (is_numeric($identificator))
        {
            return SongLyric::find($identificator);
        }
        else
        {
            $double = SongLyric::where('name', $identificator)->first();
            if ($uniqueName && $double != null) {
                return $double;
            }
            
            $song       = Song::create(['name' => $identificator]);
            $song_lyric = SongLyric::create([
                'name' => $identificator,
                'song_id' => $song->id
            ]);

            return $song_lyric;
        }
    }
}
