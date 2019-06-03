@php
    $authors_count = $song_l->authors()->count();
    $original_lyric = $song_l->song->getOriginalSongLyric();
    if($original_lyric !== NULL) {
        $original_authors_count = $original_lyric->authors()->count();
    }
    $author_a = ($original_lyric !== NULL)?"a":"A";
@endphp

@if($song_l->type !== 0) {{-- translation --}}
    @if($original_lyric !== NULL)
        Originál: <a href="{{ $original_lyric->public_url }}">{{ $original_lyric->name }}</a><br>
        @if($original_authors_count == 0)
            Autor: neznámý,
        @else
            @if($original_authors_count == 1) Autor: @else Autoři: @endif
                    
            @foreach($original_lyric->authors as $author)
                <a href="{{route('client.author', $author)}}">{{$author->name}}</a>,
            @endforeach
        @endif
    @endif

    @if($authors_count == 0)
        {{ $author_a }}utor překladu: neznámý
    @else
        @if($authors_count == 1) {{ $author_a }}utor @else {{ $author_a }}utoři @endif překladu:
        
        @foreach($song_l->authors as $author)
            <a href="{{route('client.author', $author)}}">{{$author->name}}</a>@if (!$loop->last), @endif
        @endforeach
    @endif

@else {{-- original --}}
    @if($authors_count == 0)
        Autor: neznámý
    @else
        @if($authors_count == 1) Autor: @else Autoři: @endif
                
        @foreach($song_l->authors as $author)
            <a href="{{route('client.author', $author)}}">{{$author->name}}</a>@if (!$loop->last), @endif
        @endforeach
    @endif
@endif
