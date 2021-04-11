<?php

namespace App;

use App\Services\RenderedScoreService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RenderedScore extends Model
{
    protected $fillable = ['render_config', 'filename', 'filetype', 'secondary_filetypes', 'render_config_hash'];

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

    public function scopePdf($query)
    {
        return $query->where('filetype', 'pdf');
    }

    public function scopeSvg($query)
    {
        return $query->where('filetype', 'svg');
    }
}
