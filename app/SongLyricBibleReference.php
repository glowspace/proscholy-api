<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SongLyricBibleReference extends Model
{
    protected $table = 'song_lyric_bible_reference';
    protected $fillable = ["song_lyric_id", "book", 'start_chapter', 'start_verse', 'end_chapter', 'end_verse'];
    public $incrementing = false;
    public $timestamps = false;

    public function song_lyric()
    {
        return $this->belongsTo(SongLyric::class);
    }

    public function scopeIsIncludedIn($query, $start_chapter, $start_verse, $end_chapter, $end_verse)
    {
        return $query->where(function ($q) use ($start_chapter, $start_verse, $end_chapter, $end_verse) {
            // seeked.start is between in.start and in.end
            // seeked    | ------ |
            // in     |----|
            $q->where('start_chapter', '>=', $start_chapter)
                ->where('start_verse', '>=', $start_verse)
                ->where('start_chapter', '<=', $end_chapter)
                ->where('start_verse', '<=', $end_verse);
        })->orWhere(function ($q) use ($start_chapter, $start_verse, $end_chapter, $end_verse) {
            // seeked.end is between in.start and in.end
            // seeked | ---- |
            // in         |---------|
            $q->where('end_chapter', '>=', $start_chapter)
                ->where('end_verse', '>=', $start_verse)
                ->where('end_chapter', '<=', $end_chapter)
                ->where('end_verse', '<=', $end_verse);
        })->orWhere(function ($q) use ($start_chapter, $start_verse) {
            // in.start is between seeked.start and seeked.end
            // seeked | --------- |
            // in         | -- |
            $q->where('end_chapter', '>=', $start_chapter)
                ->where('end_verse', '>=', $start_verse)
                ->where('start_chapter', '<=', $start_chapter)
                ->where('start_verse', '<=', $start_verse);
        });
    }
}
