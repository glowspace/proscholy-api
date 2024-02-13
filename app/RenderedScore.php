<?php

namespace App;

use App\Services\RenderedScoreService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RenderedScore extends Model
{
    protected $fillable = ['render_config', 'filename', 'filetype', 'secondary_filetypes', 'render_config_hash', 'frontend_display_order'];

    protected $casts = [
        'render_config' => 'array',
        'secondary_filetypes' => 'array',
    ];

    public function lilypond_parts_sheet_music(): BelongsTo
    {
        return $this->belongsTo(LilypondPartsSheetMusic::class, 'lilypond_parts_sheet_music_id', 'id');
    }

    public function external(): BelongsTo
    {
        return $this->belongsTo(External::class);
    }

    public function scopeRenderConfig($query, $render_config_data)
    {
        $rs_service = app(RenderedScoreService::class);
        return $query->where('render_config_hash', $rs_service->getRenderConfigHash($render_config_data));
    }

    public function scopeWide($query, $wide)
    {
        return $query->where('render_config->paper_width_mm', $wide ? 240 : null);
    }

    public function scopePdf($query)
    {
        return $query->where('filetype', 'pdf');
    }

    public function scopeSvg($query)
    {
        return $query->where('filetype', 'svg');
    }

    public function getPublicUrlAttribute()
    {
        // this should actually be served by nginx
        return url("/$this->filepath");
    }

    public function getFilepathAttribute()
    {
        return "rendered_scores/$this->filename.$this->filetype";
    }

    public function getContentsAttribute()
    {
        $path = Storage::path($this->filepath);
        return file_get_contents($path);
    }
}
