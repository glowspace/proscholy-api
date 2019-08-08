<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Tag extends Model
{
    protected $fillable = ['name', 'description', 'type', 'parent_tag_id'];

    public static $type_string_values = [
        'neoficiální', 'oficiální (liturgie)'
    ];

    public function getTypeStringAttribute()
    {
        return self::$type_string_values[$this->type];
    }

    public function getTypeStringValuesAttribute()
    {
        return $this->type_string_values;
    }

    public function scopeOfficials($query)
    {
        return $query->where('type', 1);
    }

    public function scopeUnofficials($query)
    {
        return $query->where('type', 0);
    }

    public function song_lyrics() : BelongsToMany
    {
        return $this->belongsToMany(SongLyric::class);
    }

    public static function getByIdOrCreateWithName($identificator)
    {
        if (is_numeric($identificator))
        {
            return Tag::find($identificator);
        }
        else
        {
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

    public function child_tags() : HasMany
    {
        return $this->hasMany(Tag::class, 'parent_tag_id');
    }

    public function parent_tag() : BelongsTo
    {
        return $this->belongsTo(Tag::class, 'parent_tag_id');
    }
}
