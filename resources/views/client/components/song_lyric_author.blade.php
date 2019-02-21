{{-- translation --}}

@php
    $authors_count = $song_l->authors()->count();
    $original_lyric = $song_l->song->getOriginalSongLyric();
@endphp

@if(! $song_l->is_original)


    @if($authors_count == 0)
        <span>Autor překladu: neznámý</span>
    @elseif($authors_count == 1)
        <span>Autor překladu: {!! $song_l->authors()->first()->getLink()!!}</span>
    @elseif($authors_count > 1)
        <span>Autoři překladu:
            @foreach($song_l->authors as $author)
                {!! $author->getLink() !!}{{ $loop->last ? '' : ', ' }}
            @endforeach
        </span>
    @endif

    @if($original_lyric !== NULL)
        , originál: <a
                href="{{route('client.song.text', $original_lyric)}}">{{$original_lyric->name}}</a>
    @endif


@else {{-- original --}}


@if($authors_count == 0)
    <span>Autor: neznámý</span>
@elseif($authors_count == 1)
    <span>Autor: {!! $song_l->authors()->first()->getLink() !!}</span>
@elseif($authors_count > 1)
    <span>Autoři:
        @foreach($song_l->authors as $author)
            {!! $author->getLink() !!}{{ $loop->last ? '' : ', ' }}
        @endforeach
    </span>
@endif


@endif
