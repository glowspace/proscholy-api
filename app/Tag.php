<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'description', 'type', 'hide_in_liturgy', 'lit_day_identificator'];

    // when updating this, do not forget to update TagSeeder.php
    protected static $groups_info = [
        0 => [
            'name' => 'K příležitostem',
            'type' => 0,
            'is_editable' => true,
            'is_regenschori' => false
        ],
        1 => [
            'name' => 'Mše svatá',
            'type' => 1,
            'is_editable' => false,
            'is_regenschori' => false
        ],
        2 => [
            'name' => 'Liturgický rok',
            'type' => 2,
            'is_editable' => false,
            'is_regenschori' => false
        ],
        3 => [
            'name' => 'Ke svatým',
            'type' => 3,
            'is_editable' => true,
            'is_regenschori' => false

        ],
        4 => [
            'name' => 'Hudební forma',
            'type' => 4,
            'is_editable' => true,
            'is_regenschori' => true
        ],
        5 => [
            'name' => 'Svátosti a pobožnosti',
            'type' => 5,
            'is_editable' => true,
            'is_regenschori' => false
        ],
        6 => [
            'name' => 'Témata a motivy',
            'type' => 6,
            'is_editable' => true,
            'is_regenschori' => false
        ],
        10 => [
            'name' => 'Historické období',
            'type' => 10,
            'is_editable' => true,
            'is_regenschori' => true
        ],
        // 40 => [
        //     'name' => 'liturgický kalendář',
        //     'type' => 40,
        //     'is_editable' => false,
        //     'is_regenschori' => true
        // ],
        // 41 => [
        //     'name' => 'atribut svatého',
        //     'type' => 41,
        //     'is_editable' => false,
        //     'is_regenschori' => true
        // ],
        50 => [
            'name' => 'Instrumentace',
            'type' => 50,
            'is_editable' => true,
            'is_regenschori' => true
        ],
        100 => [
            'name' => 'Žánr',
            'type' => 100,
            'is_editable' => true,
            'is_regenschori' => true
        ]
    ];

    public static $song_lyric_types = [0, 1, 2, 3, 4, 5, 6, 10, 40];
    public static $external_types = [50];
    public static $file_types = [50];
    public static $author_types = [10];

    public function getTypeStringAttribute()
    {
        return $this->type_string_values[$this->type];
    }

    public function getTypeStringValuesAttribute()
    {
        return array_map(function ($val) {
            return $val['name'];
        }, $this->groups_info);
    }

    public function getGroupsInfoAttribute()
    {
        return self::$groups_info;
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

    public function scopeSacredOccasion($query)
    {
        return $query->where('type', 5);
    }

    public function scopeTopic($query)
    {
        return $query->where('type', 6);
    }

    public function scopeLiturgyDay($query)
    {
        return $query->where('type', 40);
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

    public function getIsForSongsAttribute()
    {
        return in_array($this->type, self::$song_lyric_types);
    }
    public function getIsForExternalsAttribute()
    {
        return in_array($this->type, self::$external_types);
    }
    public function getIsForAuthorsAttribute()
    {
        return in_array($this->type, self::$author_types);
    }


    public static function getByIdOrCreateWithName($identificator)
    {
        if (is_numeric($identificator)) {
            return Tag::find($identificator);
        } else {
            $samename = Tag::where('name', $identificator)->first();
            if ($samename !== null) {
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
