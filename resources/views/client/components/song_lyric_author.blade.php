@php
    $authors_count = $song_l->authors()->count();
    $original_lyric = $song_l->song->getOriginalSongLyric();
@endphp

@if(! $song_l->type != 0) {{-- translation --}}

    <span>
        @if($authors_count == 0)
            Autor překladu: neznámý
        @else
            @if($authors_count == 1) Autor @else Autoři @endif překladu:
            
            @foreach($song_l->authors as $author)
                <a href="{{route('client.author', $author)}}">{{$author->name}}</a>@if (!$loop->last), @endif
            @endforeach
        @endif
        @if($original_lyric !== NULL), originál: <a
                    href="{{ $original_lyric->public_url }}">{{ $original_lyric->name }}</a>
        @endif
    </span>

@else {{-- original --}}


    @if($authors_count == 0)
        <span>Autor: neznámý</span>
    @else
        @if($authors_count == 1) Autor: @else Autoři: @endif
                
        @foreach($song_l->authors as $author)
            <a href="{{route('client.author', $author)}}">{{$author->name}}</a>@if (!$loop->last), @endif
        @endforeach
    @endif
@endif
