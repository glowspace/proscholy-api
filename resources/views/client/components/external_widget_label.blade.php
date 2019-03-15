@if($external->author !== null and $external->song_lyric !== null)
    {!! $external->author->getLink() !!} - <a
            href="{{ $external->song_lyric->public_url }}">{{$external->song_lyric->name}}</a>
@endif