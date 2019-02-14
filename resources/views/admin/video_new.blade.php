@extends('layout.layout_old')

@section('content')
    <h2>Nové video</h2>

    <a href="{{route('admin.videos')}}">Zpět do administrace</a>
    <a href="{{route('admin.todo')}}">Zpět na TO-DO list</a>

    <form action="{{route('admin.video.new.save')}}" method="post">
        @csrf
        <input autofocus name="url" placeholder="URL videa na YT"><br>

        <input type="submit" value="Uložit nové URL">
    </form>
@endsection
