@extends('layout.admin')

@section('content')
    <div class="content-padding">
    <h2>Úprava písně</h2>
        <song-lyric-edit preset-id="{{ $song_lyric->id }}" csrf="{{ csrf_token() }}"></song-lyric-edit>
    </div>
@endsection