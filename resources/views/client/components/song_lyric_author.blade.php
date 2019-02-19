{{-- translation --}}
@if(! $song_l->is_original)


    @if($song_l->authors()->count() == 0)
        <span>Autor překladu: neznámý</span>
    @elseif($song_l->authors()->count() == 1)
        <span>Autor překladu: {!! $song_l->authors()->first()->getLink()!!}</span>
    @elseif($song_l->authors()->count() > 1)
        <span>Autoři překladu:
            @foreach($song_l->authors as $author)
                {!! $author->getLink() !!}{{ $loop->last ? '' : ', ' }}
            @endforeach
        </span>
    @endif

    @if($song_l->song->getOriginalLyric() !== null)
        , originál: <a
                href="{{route('client.song.text', $song_l->song->getOriginalLyric())}}">{{$song_l->song->getOriginalLyric()->name}}</a>
    @endif


@else {{-- original --}}


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


@endif
