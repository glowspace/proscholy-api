@extends('layout.admin')

@section('title-edit', $song_lyric->name)

@section('content-withmenu')
    <div class="__container-fluid">
        @if ($song_lyric->is_arrangement)
        <h1 class="h2">Úprava aranže</h1>
        @else
        <h1 class="h2">Úprava písně</h1>
        @endif
        <song-lyric-edit preset-id="{{ $song_lyric->id }}" csrf="{{ csrf_token() }}"></song-lyric-edit>
    </div>
@endsection
