@extends('layout.admin')

@section('title-edit', $song_lyric->name)

@section('content-withmenu')
    <song-lyric-edit preset-id="{{ $song_lyric->id }}"></song-lyric-edit>
@endsection
