@extends('layout.admin')

@section('title', '● '.$song_lyric->name.' – administrace ProScholy.cz')

@section('content-withmenu')
    <div class="__container-fluid">
    @if ($song_lyric->is_arrangement)
    <h2>Úprava aranže</h2>
    @else
    <h2>Úprava písně</h2>
    @endif
        <song-lyric-edit preset-id="{{ $song_lyric->id }}" csrf="{{ csrf_token() }}"></song-lyric-edit>
    </div>
@endsection