<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VisitAggregate extends Model
{
    protected $fillable = ['visitable_type', 'visitable_id', 'count_week', 'count_total'];

    public $timestamps = false;

    public function setCreatedAtAttribute()
    {
        // do nothing
    }

    public function song_lyric(): MorphTo
    {
        return $this->morphTo(SongLyric::class, 'visitable');
    }
}
