<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NewsItem extends Model
{
    protected $fillable = ['text', 'fa_icon', 'link', 'link_type', 'starts_at', 'expires_at', 'is_published'];

    private $link_type_string_values
    = [
        'NORMAL' => 'odkaz (stejné okno)',
        'BLANK' => 'odkaz (nové okno)',
        'NUXTLINK' => 'odkaz v rámci zpěvníku (např. /o-zpevniku)',
        'IMAGE' => 'obrázek',
        'YOUTUBE' => 'youtube video',
        'VIDEO' => 'video',
        'IFRAME' => 'náhled (iframe)',
        'PDF' => 'pdf',
    ];

    public function getLinkTypeStringValuesAttribute()
    {
        return $this->link_type_string_values;
    }

    public function scopeActive($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->where(function ($q) use ($now) {
            $q->where('starts_at', '<=', $now)->orWhereNull('starts_at');
        })->where(function ($q) use ($now) {
            $q->where('expires_at', '>', $now)->orWhereNull('expires_at');
        })->where('is_published', true);
    }
}
