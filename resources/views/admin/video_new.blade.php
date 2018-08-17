@extends('layout')

@section('content')
    <h2>Nové video</h2>

    <form action="{{route('admin.video.new.save')}}" method="post">
        @csrf
        <input autofocus name="url" placeholder="URL videa na YT"><br>

        <input type="submit" value="Uložit nové URL">
    </form>
@endsection
