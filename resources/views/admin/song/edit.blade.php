@extends('layout.admin')

@section('title-edit', $song_lyric->name)

@section('content-withmenu')
    <song-lyric-edit preset-id="{{ $song_lyric->id }}" csrf="{{ csrf_token() }}"></song-lyric-edit>
@endsection
