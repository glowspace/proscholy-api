@if($song_l->authors()->count() == 0)
    <span>Autor: neznámý</span>
@elseif($song_l->authors()->count() == 1)
    <span>Autor: {!! $song_l->authors()->first()->getLink()!!}</span>
@elseif($song_l->authors()->count() > 1)
    <span>Autoři {{$song_l}}: {{$song_l->authors->pluck('name')->implode(', ') }}</span>
@endif