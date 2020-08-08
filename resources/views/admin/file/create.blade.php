@extends('layout.admin')

@section('title-suffixed', 'Nahrát nový soubor')

@section('content-withmenu')
    <file-create
        @if (isset($song_lyric))
            song-lyric="{{$song_lyric->id}}"
        @endif
    ></file-create>
@endsection
