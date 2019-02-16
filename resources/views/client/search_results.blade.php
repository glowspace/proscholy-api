@extends('layout.layout')

@section('navbar')

@endsection

@section('content')
    <div class="content-padding">
        <h1>Výsledky vyhledávání pro {{$phrase}}</h1>

        <h2>Písně</h2>

        <table class="table">
            @forelse($song_lyrics as $song_lyric)
                <tr>
                    <td>
                        <a href="{{$song_lyric->id}}">{{$song_lyric->getSearchTitle()}}</a>
                        - {{$song_lyric->getSearchText()}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td>
                        <i>Žádná píseň s tímto názvem nebyla nalezena.</i>
                    </td>
                </tr>
            @endforelse
        </table>

        <h2>Autoři</h2>

        <table class="table">
            @forelse($authors as $author)
                <tr>
                    <td>
                        <a href="{{$author->id}}">{{$author->getSearchTitle()}}</a> - {{$author->getSearchText()}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td>
                        <i>Žádný autor nebyl nalezen.</i>
                    </td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
