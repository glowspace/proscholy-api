@if($song_l->authors()->count() == 0)
    <span>Autor: neznámý</span>
@elseif($song_l->authors()->count() == 1)
    <span>Autor: {!! $song_l->authors()->first()->getLink()!!}</span>
@elseif($song_l->authors()->count() > 1)
    <span>Autoři:
        @foreach($song_l->authors as $author)
            {{$song_l->authors->pluck('name')->implode(', ') }}}}
        @endforeach
    </span>
@endif