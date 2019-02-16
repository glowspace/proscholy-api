@extends('layout.layout')

@section('navbar')

@endsection

@section('content')
    <div class="content-padding">
        <h1>Výsledky vyhledávání pro {{$phrase}}</h1>


        <h2>Písně</h2>

        <table class="table">
            @foreach($song_lyrics as $song_lyric)
                <tr>
                    <td>
                        <a href="{{$song_lyric->id}}">{{$song_lyric->getSearchTitle()}}</a> - {{$song_lyric->getSearchText()}}
                    </td>
                </tr>
            @endforeach
        </table>

        <h2>Autoři</h2>

        <table class="table">
            @foreach($authors as $author)
                <tr>
                    <td>
                        <a href="{{$author->id}}">{{$author->getSearchTitle()}}</a> - {{$author->getSearchText()}}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
