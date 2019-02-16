@extends('layout.layout_old')

@section('content')

    <h2>Abecední seznam písní</h2>

    @foreach($songs as $song)
            <a href="{{route('song_lyrics.single', ['id'=> $song->id])}}">{{$song->name}}</a><br>

    @endforeach
@endsection
