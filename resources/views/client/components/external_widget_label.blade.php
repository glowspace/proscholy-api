@foreach ($external->authors as $author)
    <a href="{{route('client.author', $author)}}">{{$author->name}}</a>@if (!$loop->last), @endif
@endforeach

@if ($external->song_lyric)
- <a href="{{ $external->song_lyric->public_url }}">{{$external->song_lyric->name}}</a>
@endif