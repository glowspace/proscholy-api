@php
    $authors_count = $song_l->authors()->count();
    $original_lyric = $song_l->song->getOriginalSongLyric();
    if($original_lyric !== NULL) {
        $original_authors_count = $original_lyric->authors()->count();
    }
@endphp
Píseň {{ $song_l->name }},
@if($song_l->type !== 0) {{-- translation --}}
    @if($original_lyric !== NULL) originál: {{ $original_lyric->name }},
        @if($original_authors_count == 0) autor: neznámý,
        @else
            @if($original_authors_count == 1) autor: @else autoři: @endif
            @foreach($original_lyric->authors as $author) {{$author->name}},
            @endforeach
        @endif
    @endif
    @if($authors_count == 0) autor překladu: neznámý
    @else
        @if($authors_count == 1) autor @else autoři @endif překladu:
        @foreach($song_l->authors as $author) {{$author->name}}@if (!$loop->last), @endif
        @endforeach
    @endif
@else {{-- original --}}
    @if($authors_count == 0) autor: neznámý
    @else
        @if($authors_count == 1) autor: @else autoři: @endif
        @foreach($song_l->authors as $author) {{$author->name}}@if (!$loop->last), @endif
        @endforeach
    @endif
@endif
