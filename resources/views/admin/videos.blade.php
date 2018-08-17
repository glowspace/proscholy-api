@extends('layout')

@section('content')

    <a href="{{route('admin.dashboard')}}">Zpět do administrace</a>

    <h2>Zvolte video, které chcete upravit:</h2>

    <h3>TO-DO</h3>
    @foreach($todo as $video)
        <a href="{{route('admin.video.edit',['id'=>$video->id])}}">{{$video->generateTitle()}}</a><br>
    @endforeach

    <h3>Všechna videa</h3>

    @foreach($videos as $video)
        <a href="{{route('admin.video.edit',['id'=>$video->id])}}">{{$video->generateTitle()}}</a><br>
    @endforeach


@endsection
