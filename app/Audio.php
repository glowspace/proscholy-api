<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Audio
 *
 * @property int|null $type
 * @property int|null $song_lyric_id
 * @property int|null $licence_type
 * @property string|null $licence_content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Audio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Audio whereLicenceContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Audio whereLicenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Audio whereSongLyricId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Audio whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Audio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Audio extends Model
{
    //
}
