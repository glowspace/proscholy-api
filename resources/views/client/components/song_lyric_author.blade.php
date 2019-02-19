@if($song_l->authors()->count() == 0)
    <span>Autor: neznámý</span>
@elseif($song_l->authors()->count() == 1)
    <span>Autor: {!! $song_l->authors()->first()->getLink()!!}</span>
@elseif($song_l->authors()->count() > 1)
    <span>Autoři:
        @foreach($song_l->authors as $author)
            {!! $author->getLink() !!}{{ $loop->last ? '' : ', ' }}
        @endforeach
    </span>
@endif