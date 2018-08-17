@extends('layout')

@section('content')

    <h2>Abecední seznam písní</h2>

    @foreach($songs as $song)
        @if(isset($song->song_id))
            <a href="{{route('translation.single', ['id'=> $song->id])}}">{{$song->name}}</a><br>
        @else
            <a href="{{route('song.single', ['id'=> $song->id])}}">{{$song->name}}</a><br>
        @endif
    @endforeach
@endsection
