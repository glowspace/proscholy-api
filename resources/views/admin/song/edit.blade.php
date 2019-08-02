@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
    <h2>Úprava písně</h2>
        <song-lyric-edit preset-id="{{ $song_lyric->id }}" csrf="{{ csrf_token() }}"></song-lyric-edit>
    </div>
@endsection