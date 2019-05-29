<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface ISource
{
    public function getSourceType() : int;
    public function authors() : BelongsToMany;
    public function song_lyric() : BelongsTo;
    public function getId(): int;
    public function getMediaId();
}