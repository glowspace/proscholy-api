<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RenderedScore extends Model
{
    protected $fillable = ['layout_config', 'filename', 'filetype', 'secondary_filetypes'];

    protected $casts = [
        'layout_config' => 'array',
        'secondary_filetypes' => 'array'
    ];

    public function lilypond_parts_sheet_music(): BelongsTo
    {
        return $this->belongsTo(LilypondPartsSheetMusic::class, 'lilypond_parts_sheet_music_id', 'id');
    }

    public function external(): BelongsTo
    {
        return $this->belongsTo(External::class);
    }

    public function getFilepathsAttribute()
    {
        $filepaths = [];
        # code...
        $filetypes = [$this->filetype, ...($secondary_filetypes ?? [])];
        foreach ($filetypes as $ft) {
            // $filepaths[] = 
        }
    }
}
