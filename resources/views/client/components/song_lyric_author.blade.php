{{-- translation --}}

@php
    $authors_count = $song_l->authors()->count();
    $original_lyric = $song_l->song->getOriginalSongLyric();
@endphp

@if(! $song_l->is_original)

<span>
    @if($authors_count == 0)
        Autor překladu: neznámý
    @elseif($authors_count == 1)
        Autor překladu: {!! $song_l->authors()->first()->getLink()!!}
    @elseif($authors_count > 1)
        Autoři překladu:
        @foreach($song_l->authors as $author)
            {!! $author->getLink() !!}{{ $loop->last ? '' : ', ' }}
        @endforeach
    @endif
    @if($original_lyric !== NULL), originál: <a
                href="{{ $original_lyric->public_url }}">{{ $original_lyric->name }}</a>
    @endif
</span>

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
