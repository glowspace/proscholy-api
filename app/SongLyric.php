<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Support\Arr;
use App\Traits\Lockable;

use App\Helpers\Chord;
use App\Helpers\ChordSign;
use App\Helpers\ChordQueue;

/**
 * App\SongLyric
 *
 * @property int                                                           $id
 * @property string|null                                                   $name
 * @property int|null                                                      $is_authorized
 * @property int|null                                                      $is_original
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
class SongLyric extends Model implements ISearchResult
{
    // Laravel Scout Trait used for full-text searching
    // Lockable Trait for enabling to "lock" the model while editing
    use Searchable, Lockable;

    protected $fillable
        = [
            'name',
            'song_id',
            'lyrics',
            'id',
            'is_original',
            'is_authorized',
            'lang',
            'creating_at',
            'has_anonymous_author'
        ];

    public $lang_string = [
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

    public function getLanguageName()
    {
        return $this->lang_string[$this->lang];
    }

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function getLink()
    {
        return route('client.song.text', ['id' => $this->id]);
    }

    public function externals()
    {
        return $this->hasMany(External::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
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

    public function scoreExternals()
    {
        return $this->externals()->where('type', 4)->orderBy('is_featured', 'desc');
    }
    
    public function scoreFiles()
    {
        return $this->files()->where('type', 3);
    }

    /*
     * Merged multi type category-filtered external collections
     */
    public function audioTracks()
    {
        return $this->spotifyTracks->merge($this->soundcloudTracks);
    }

    /*
     * Mixed type count (for blade menu badge)
     */
    public function scoresCount()
    {
        return $this->scoreExternals()->count() + $this->scoreFiles()->count();
    }

    // the reason for existence of the domestic characteristic
    // is the case when there are multiple SongLyrics under one Song and no original one
    // which is basically not recommended but permitted
    // - consider merging domestic/orignal in the future for simplicity (depending on practical usage)
    public function isDomestic()
    {
        return $this->name === $this->song->name;
    }

    public function isDomesticOrphan()
    {
        return $this->isDomestic() && ! $this->hasSiblings();
    }

    public function getSiblings()
    {
        return $this->song->song_lyrics()->where('id', '!=', $this->id);
    }

    public function hasSiblings()
    {
        return $this->getSiblings()->count() > 0;
    }

    // basically Cuckoo shouldn't be alone really
    public function isCuckoo()
    {
        return ! $this->isDomestic();
    }

    public static function getByIdOrCreateWithName($identificator)
    {
        if (is_numeric($identificator))
        {
            return SongLyric::find($identificator);
        }
        else
        {
            $song       = Song::create(['name' => $identificator]);
            $song_lyric = SongLyric::create([
                'name' => $identificator,
                'song_id' => $song->id
            ]);

            return $song_lyric;
        }
    }

    // FOR THE NEW FRONTEND VIEWER
    public function getFormattedLyrics(){
        $lines = explode("\n", $this->lyrics);

        $output = "";
        $chordQueue = new ChordQueue();

        foreach ($lines as $line){
            $output .= '<div class="song-line">'.$this->processLine($line, $chordQueue).'</div>';
        }

        return $output;
    }

    private function processLine($line, $chordQueue) {
        $chords = array();
        $currentChordText = "";
        $line = trim($line);
        
        // starting of a line, notify Chord "repeater" if we are in a verse
        if (strlen($line) > 0 && is_numeric($line[0])) {
            $chordQueue->notifyVerse($line[0]);
        }

        for ($i = 0; $i < strlen($line); $i++){
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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Preserve only attributes that are meant to be searched in
        $searchable = Arr::only($array, ['name', 'lyrics', 'description']);

        return $searchable;
    }

    // implementing INTERFACE ISearchResult

    public function getSearchTitle()
    {
        return $this->name;
    }

    public function getSearchText()
    {
        return $this->lyrics;
    }
}
