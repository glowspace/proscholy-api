@extends('layout')

@section('content')
    @if(empty($song))
        <h2>Nová píseň</h2>

        <form action="{{route('admin.song.new.save')}}" method="post">
            @csrf
            <input autofocus name="name" placeholder="originální název písně"><br>
            <i>Pozor jestli to není překlad</i><br>

            <input name="translation_name" placeholder="jméno prvního překladu (pokud nějaký existuje a chcete jej vytvořit)">

            <input type="submit">
        </form>
    @else

    @endif

@endsection
