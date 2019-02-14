@extends('layout.layout_old')

@section('content')
    @if(empty($song))
        <h2>Návrh na píseň k zařazení</h2>

        <form action="{{route('admin.song.create.save')}}" method="post">
            @csrf
            <input autofocus name="name" placeholder="jméno songu"><br>

            <input name="translation_name" placeholder="jméno prvního překladu">

            <input type="submit">
        </form>
    @else
        <h2>Úprava: píseň </h2>
    @endif

@endsection
