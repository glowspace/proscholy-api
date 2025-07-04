<?php

namespace App;

use App\Notifications\SongLyricUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Traits\Lockable;
use App\Scopes\ExcludeEvangelicalOnlySongsScope;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Services\LilypondClientService;

// use App\Helpers\Chord;
// use App\Helpers\ChordSign;
// use App\Helpers\ChordQueue;
// use App\Helpers\SongPart;
use App\Helpers\SongLyricHelper;
use App\Jobs\RenderOldSongLyricLilypond;
use App\Services\LilypondPartsService;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;

use App\Elastic\SongLyricSearchableTrait;
use App\Scopes\EvangelicalSongsScope;
use App\Scopes\EKSongsScope;
use Illuminate\Support\Facades\Request;

/**
 * App\SongLyric
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $lyrics
 * @property int|null $is_opensong
 * @property int|null $lang_id
 * @property int|null $song_id
 * @property int|null $licence_type
 * @property string|null $licence_content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Author[] $authors
 * @property-read \App\Song|null $song
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
 * @property string $lang
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\External[] $externals
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SongLyric whereLang($value)
 */
class SongLyric extends Model
{
    use SongLyricSearchableTrait,
        // Lockable Trait for enabling to "lock" the model while editing
        Lockable,
        SoftDeletes,
        RevisionableTrait,
        HasFactory,
        Notifiable;

    protected $revisionCleanup = true;
    protected $historyLimit = 200;
    protected $revisionCreationsEnabled = true;
    protected $dontKeepRevisionOf = ['creating_at', 'created_at', 'updating_at', 'updating_user_id', 'bible_refs_osis'];

    protected $dispatchesEvents = [
        'created' => \App\Events\SongLyricCreated::class
    ];

    protected $fillable
    = [
        'name',
        'song_id',
        'id',
        // 'is_original',
        // 'is_authorized',
        'type',
        'lang',
        'creating_at',
        'has_anonymous_author',
        'has_chords',
        'user_creator_id',
        'licence_type',
        'only_regenschori',
        'capo',
        'liturgy_approval_status',
        'arrangement_of',
        'lilypond_key_major',
        'song_number',
        'bible_refs_src',
        'bible_refs_osis',
        'secondary_name_1',
        'secondary_name_2',
        'licence_type_cc',
        'admin_note',
        'is_sealed',
        'revision_n_tags',
        'revision_n_authors',
        'revision_n_songbook_records',
        'hymnology'
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
        'pt' => 'portugalština',
        'it' => 'italština',
        'sv' => 'svahilština',
        'he' => 'hebrejština',
        'cr' => 'chorvatština',
        'cu' => 'staroslověnština',
        // 'wtf' => 'jazyk domorodých kmenů jižní Oceánie',
        'mixed' => 'vícejazyčná píseň'
    ];

    private static $liturgy_approval_status_string_values = [
        0 => 'bez vyjádření ČBK',
        1 => 'schváleno ČBK pro liturgii',
        2 => 'nevhodné pro liturgii (neveřejné)',
    ];

    private static $licence_type_cc_string_values = [
        'UNSET' => 'neuvedeno',
        'BY' => 'BY (uveďte původ)',
        'BY_SA' => 'BY-SA (uv. původ, zachovejte licenci)',
        'BY_ND' => 'BY-ND (uv. původ, nezpracovávejte)',
        'BY_NC' => 'BY-NC (uv. původ, neužívejte komerčně)',
        'BY_NC_SA' => 'BY-NC-SA (uv. původ, ne-komerčně, zach. licenci)',
        'BY_NC_ND' => 'BY-NC-ND (uv. původ, ne-komerčně, nezprac.)',
        'PROPRIETARY' => 'proprietární (smlouva s MS)',
        'PUBLIC_DOMAIN' => 'volné dílo (uplynula doba trvání majetkových práv)',
        'PROPRIETARY_EVANGELICAL' => 'proprietární (pouze Evangelický zpěvník)',
        'PUBLISHABLE_EVANGELICAL' => 'licence Evangelický zpěvník (dovoluje publikaci jinde)'
    ];

    private static $lilypond_key_major_string_values = [
        "c" => '0 (C / a)',
        "g" => '1# (G / e)',
        "d" => '2# (D / h)',
        "a" => '3# (A / fis)',
        "e" => '4# (E / cis)',
        "b" => '5# (H / gis)',
        "fis" => '6# (Fis / dis)',
        "cis" => '7# (Cis / ais)',
        "ces" => '7b (Ces / as)',
        "ges" => '6b (Ges / es)',
        "des" => '5b (Des / b)',
        "as" => '4b (As / f)',
        "es" => '3b (Es / c)',
        "bes" => '2b (B / g)',
        "f" => '1b (F / d)'
    ];

    protected static function booted()
    {
        if (Auth::check()) {
            return;
        }

        if (Request::header('Filter-Content') == 'ez') {
            // limit the songs to only those that are in the Evangelical songbook
            static::addGlobalScope(new EvangelicalSongsScope);
        } else if (Request::header('Filter-Content') == 'ek') {
            static::addGlobalScope(new EKSongsScope);
        } else {
            static::addGlobalScope(new ExcludeEvangelicalOnlySongsScope);
        }
    }

    public function getPublicUrlAttribute()
    {
        $slug = Str::slug($this->name);

        $url = url("/pisen/$this->id/$slug");
        $url = str_replace('api.proscholy', 'zpevnik.proscholy', $url);

        return $url;
    }

    public function getPublicRouteAttribute()
    {
        return str_replace(url(""), "", $this->public_url);
    }

    public function getLyricsNoChordsAttribute()
    {
        $str = preg_replace(
            array('/-/', '/\[[^\]]+\]/', '/@[^\s]+/'),
            array("", "", "", ""),
            $this->lyrics
        );

        return trim($str);
    }

    public function getLyricsNoChordsNoCommentsAttribute()
    {
        $str = preg_replace(
            array('/^\s*#.*/m'),
            array(""),
            $this->lyrics_no_chords
        );

        return trim($str);
    }

    // TODO: implement
    public function getIsEmptyAttribute()
    {
        // return $this->lyrics == null
        //     && $this->externals()->count() +
        //     $this->files()->count() == 0
        //     &&
    }

    public function getHasLyricsAttribute(): bool
    {
        return !empty($this->lyrics);
    }

    // public function getHasMediaAttribute() : bool
    // {
    //     return
    //         $this->externals()->media()->exists() ||
    //         $this->files()->audio()->exists();
    // }

    // public function getHasSheetMusicAttribute() : bool
    // {
    //     return
    //         $this->externals()->scores()->exists() ||
    //         $this->files()->scores()->exists();
    // }


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

    public function getLiturgyApprovalStatusStringAttribute()
    {
        return self::$liturgy_approval_status_string_values[$this->liturgy_approval_status];
    }

    public function getLiturgyApprovalStatusStringValuesAttribute()
    {
        return self::$liturgy_approval_status_string_values;
    }

    public function getIsApprovedForLiturgyAttribute()
    {
        return $this->liturgy_approval_status == 1;
    }

    // helper method, so this can be included in graphql schema
    public function getAuthorshipTypeStringValuesAttribute()
    {
        return AuthorSongLyric::$authorship_type_string_values;
    }

    public function getLicenceTypeCCStringValuesAttribute()
    {
        return self::$licence_type_cc_string_values;
    }

    public function getLilypondKeyMajorStringValuesAttribute()
    {
        return self::$lilypond_key_major_string_values;
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    // todo: make obsolete (together with an graphql endpoint)
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function authors_pivot(): BelongsToMany
    {
        return $this->belongsToMany(Author::class)
            ->withPivot('authorship_type', 'id')
            ->using(AuthorSongLyric::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function externals(): HasMany
    {
        return $this->hasMany(External::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function visit_aggregate(): MorphOne
    {
        return $this->morphOne(VisitAggregate::class, 'visitable');
    }

    // has one relations (for performance boost)

    public function lyrics(): HasOne
    {
        return $this->hasOne(SongLyricLyrics::class);
    }

    public function lilypond_src(): HasOne
    {
        return $this->hasOne(SongLyricLilypondSrc::class);
    }

    public function bible_reference(): HasMany
    {
        return $this->hasMany(SongLyricBibleReference::class);
    }

    public function lilypond_parts_sheet_music(): HasOne
    {
        return $this->hasOne(LilypondPartsSheetMusic::class)->withDefault(function ($lp_sheet_music, $song_lyric) {
            $lp_sheet_music->lilypond_parts = [[
                'src' => '',
                'name' => '1',
                'key_major' => $song_lyric->lilypond_key_major ?? 'c',
                'time_signature' => '4/4'
            ]];

            $lp_sheet_music->score_config = app(LilypondPartsService::class)->getDefaultScoreConfigData(false);
            $lp_sheet_music->global_src = '';
            $lp_sheet_music->sequence_string = '';
        });
    }

    public function lilypond_svg(): HasOne
    {
        return $this->hasOne(SongLyricLilypondSvg::class);
    }

    public function lilypond_rendered_scores(): HasManyThrough
    {
        return $this->hasManyThrough(RenderedScore::class, LilypondPartsSheetMusic::class, null, 'lilypond_parts_sheet_music_id');
    }

    public function external_rendered_scores(): HasManyThrough
    {
        return $this->hasManyThrough(RenderedScore::class, External::class, null, 'external_id');
    }

    //  -----------

    public function arrangements(): HasMany
    {
        return $this->hasMany(SongLyric::class, 'arrangement_of', 'id');
    }

    public function arrangement_source(): BelongsTo
    {
        return $this->belongsTo(SongLyric::class, 'arrangement_of', 'id');
    }

    public function getIsArrangementAttribute(): bool
    {
        return $this->arrangement_of !== null;
    }

    public function getHasArrangementsAttribute(): bool
    {
        return $this->arrangements()->count() > 0;
    }

    // Czech Evangelical Songbook
    public function getEzNumberAttribute(): string|null
    {
        // only to be used with evangelicky zpevnik songbooks (see song_lyric.graphql, note a EZ scope)
        return $this->songbook_records->first()?->pivot->number;
    }

    public function getRichNameAttribute(): string
    {
        $name = $this->name;

        if ($this->is_arrangement) {
            $name .= " (ar. písně " . $this->arrangement_source->name;

            $author_names = $this->authors()->select('name')->get()->pluck('name');

            if ($author_names->count() > 0) {
                $name .= ", autoři: ";
                $name .= $author_names->join(', ');
            }

            $name .= ")";
        }

        return $name;
    }

    public function songbook_records(): BelongsToMany
    {
        return $this->belongsToMany(Songbook::class, "songbook_records")
            ->withPivot('number', 'placeholder', 'id', 'song_name')
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
        return $query->whereHas('authors', function ($q) {
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
            ->orWhereHas('lyrics');
    }

    /*
     * Real type collections
     */
    public function spotifyTracks()
    {
        return $this->externals()->where('media_type', 'spotify')->orderBy('is_featured', 'desc');
    }

    public function soundcloudTracks()
    {
        return $this->externals()->where('media_type', 'soundcloud')->orderBy('is_featured', 'desc');
    }

    public function youtubeVideos()
    {
        return $this->externals()->where('media_type', 'youtube')->orderBy('is_featured', 'desc');
    }

    public function audioFiles()
    {
        return $this->files()->audio();
    }

    public function scoreExternals()
    {
        return $this->externals()->scores()->orderBy('is_featured', 'desc');
    }

    public function scoreFiles()
    {
        return $this->files()->scores();
    }

    public function getSiblings()
    {
        return $this->song->songLyrics()->where('id', '!=', $this->id)->get();
    }

    public function hasSiblings()
    {
        return $this->getSiblings()->count() > 0;
    }

    // todo: remove
    public function getFormattedLyrics()
    {
        $output = "";

        // type :: [App/Helpers/SongPart]
        $parts = SongLyricHelper::getLyricsRepresentation($this);

        $firstRefrain = current(array_filter($parts, function ($part) {
            return $part->isRefrain();
        }));

        foreach ($parts as $song_part) {
            if ($song_part->isRefrain() && $song_part->isEmpty()) {
                // substitute by the first refrain
                $subst = clone $firstRefrain;

                if ($song_part->isHidden()) {
                    $subst->setHidden(true);
                } else {
                    $subst->setHiddenText(true);
                }
                $output .= $subst->toHTML();
            } else {
                $output .= $song_part->toHTML();
            }
        }

        return $output;
    }

    public function getSongParts()
    {
        $parts = SongLyricHelper::getLyricsRepresentation($this);

        $firstRefrain = current(array_filter($parts, function ($part) {
            return $part->isRefrain();
        }));

        $newParts = [];

        foreach ($parts as $song_part) {
            if ($song_part->isRefrain() && $song_part->isEmpty()) {
                // substitute by the first refrain
                $subst = clone $firstRefrain;

                if ($song_part->isHidden()) {
                    $subst->setHidden(true);
                } else {
                    $subst->setHiddenText(true);
                }
                $newParts[] = $subst;
            } else {
                $newParts[] = $song_part;
            }
        }

        return $newParts;
    }

    // todo: make obsolete
    public static function getByIdOrCreateWithName($identificator, $uniqueName = false)
    {
        if (is_numeric($identificator)) {
            return SongLyric::find($identificator);
        } else {
            $double = SongLyric::where('name', $identificator)->first();
            if ($uniqueName && $double != null) {
                return $double;
            }

            $song = Song::create(['name' => $identificator]);
            $song_lyric = SongLyric::create([
                'name' => $identificator,
                'song_id' => $song->id
            ]);

            return $song_lyric;
        }
    }

    // required for the laravel-notification-channels/discord package
    public function routeNotificationForDiscord()
    {
        return config('services.discord.webhook_url');
    }
}
