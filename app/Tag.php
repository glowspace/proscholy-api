<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'description', 'type', 'parent_tag_id'];

    public static $type_string = [
        'neoficiální', 'oficiální (liturgie)'
    ];

    public function getTypeText()
    {
        return self::$type_string[$this->type];
    }

    public function scopeOfficials($query)
    {
        return $query->where('type', 1);
    }

    public function scopeUnofficials($query)
    {
        return $query->where('type', 0);
    }

    public function song_lyrics()
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
            $tag = Tag::create([
                'name' => $identificator,
            ]);

            return $tag;
        }
    }

    // public function isAncestorOf()
    // {

    // }

    public function child_tags()
    {
        return $this->hasMany(Tag::class, 'parent_tag_id');
    }

    public function parent_tag()
    {
        return $this->belongsTo(Tag::class, 'parent_tag_id');
    }

    // public function getParentTag()
    // {
    //     if ($this->parent_tag_id !== NULL) {
    //         return Tag::find($this->parent_tag_id);
    //     }

    //     return NULL;
    // }
}
