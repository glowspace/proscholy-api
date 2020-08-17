<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'description', 'type'];

    // todo: make obsolete???
    public static $type_string_values = [
        0 => 'příležitosti',
        1 => 'litugie (část)',
        2 => 'liturgická doba',
        3 => 'ke svatým',
        4 => 'hudební forma',
        10 => 'historické období',
        50 => 'instrumentace',
        100 => 'žánr'
    ];

    public static $song_lyric_types = [0, 1, 2, 3, 4, 10];
    public static $external_types = [50];
    public static $file_types = [50];
    public static $author_types = [10];

    public function getTypeStringAttribute()
    {
        return self::$type_string_values[$this->type];
    }

    public function getTypeStringValuesAttribute()
    {
        return $this->type_string_values;
    }

    public function scopeSongLyricsTags($query)
    {
        return $query->whereIn('type', self::$song_lyric_types);
    }

    public function scopeExternalTags($query)
    {
        return $query->whereIn('type', self::$external_types);
    }

    public function scopeFileTags($query)
    {
        return $query->whereIn('type', self::$file_types);
    }

    public function scopeAuthorTags($query)
    {
        return $query->whereIn('type', self::$author_types);
    }

    public function scopeLiturgyPart($query)
    {
        return $query->where('type', 1);
    }


    public function scopeGeneric($query)
    {
        return $query->where('type', 0);
    }

    public function scopeGenre($query)
    {
        return $query->where('type', 100);
    }

    public function scopeInstrumentation($query)
    {
        return $query->where('type', 50);
    }

    public function scopeLiturgyPeriod($query)
    {
        return $query->where('type', 2);
    }

    public function scopeHistoryPeriod($query)
    {
        return $query->where('type', 10);
    }

    public function scopeSaints($query)
    {
        return $query->where('type', 3);
    }

    public function scopeMusicalForm($query)
    {
        return $query->where('type', 4);
    }

    public function song_lyrics(): MorphToMany
    {
        return $this->morphedByMany(SongLyric::class, 'taggable');
    }

    public function externals(): MorphToMany
    {
        return $this->morphedByMany(External::class, 'taggable');
    }

    public function files(): MorphToMany
    {
        return $this->morphedByMany(File::class, 'taggable');
    }

    public function authors(): MorphToMany
    {
        return $this->morphedByMany(Author::class, 'taggable');
    }


    public static function getByIdOrCreateWithName($identificator)
    {
        if (is_numeric($identificator)) {
            return Tag::find($identificator);
        } else {
            $samename = Tag::where('name', $identificator)->first();
            if ($samename !== NULL) {
                return $samename;
            }

            $tag = Tag::create([
                'name' => $identificator,
            ]);

            return $tag;
        }
    }

    // todo: make obsolete
    public function child_tags(): HasMany
    {
        return $this->hasMany(Tag::class, 'parent_tag_id');
    }

    // todo: make obsolete
    public function parent_tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'parent_tag_id');
    }
}
