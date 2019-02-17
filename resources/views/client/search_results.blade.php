@extends('layout.master')

@section('navbar')

@endsection

@section('content')
    <div class="content-padding">
        <h1>Vyhledávání</h1>

        <form method="POST" action="{{route('client.search')}}">
            @csrf
            <input class="form-control" name="query"
                   placeholder="Zadejte název písně (třeba Ať požehnán je Bůh)" value="{{$phrase}}" autofocus>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </form>

        <h2>Písně</h2>

        <table class="table">
            @forelse($song_lyrics as $song_lyric)
                <tr>
                    <td>
                        <i class="fas fa-music"></i> <a
                                href="{{route('client.song.text',$song_lyric)}}">{{$song_lyric->getSearchTitle()}}</a>
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
