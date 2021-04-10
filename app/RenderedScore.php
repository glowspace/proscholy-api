<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RenderedScore extends Model
{
    protected $fillable = ['render_config', 'filename', 'filetype', 'secondary_filetypes', 'is_rendered'];

    protected $casts = [
        'layout_config' => 'array',
        'secondary_filetypes' => 'array',
        'is_rendered' => 'boolean'
    ];

    public function lilypond_parts_sheet_music(): BelongsTo
    {
        return $this->belongsTo(LilypondPartsSheetMusic::class, 'lilypond_parts_sheet_music_id', 'id');
    }

    public function external(): BelongsTo
    {
        return $this->belongsTo(External::class);
    }

    public function scopeRendered($query)
    {
        return $query->where('is_rendered', true);
    }

    public function scopePdf($query)
    {
        return $query->where('filetype', 'pdf');
    }

    public function scopeSvg($query)
    {
        return $query->where('filetype', 'svg');
    }

    public function getFilepathWithoutExtensionAttribute()
    {
        // $filepaths = [];
        // # code...
        // $filetypes = [$this->filetype, ...($secondary_filetypes ?? [])];
        // foreach ($filetypes as $ft) {
        //     // $filepaths[] = 
        // }
    }
}
