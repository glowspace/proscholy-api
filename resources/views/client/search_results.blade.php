@extends('layout.layout')

@section('navbar')

@endsection

@section('content')
    <div class="content-padding">


        <h1>Výsledky vyhledávání {{$phrase}}</h1>

        <table class="table">
            @foreach($song_lyrics as $song)
                <tr>
                    <td>{{$song->name}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
