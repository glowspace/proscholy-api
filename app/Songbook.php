<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Songbook extends Model
{
    public function songTranslations()
    {
        return $this->belongsToMany(SongTranslation::class);
    }
}
