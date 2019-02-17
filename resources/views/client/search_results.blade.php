@extends('layout.master')

@section('navbar')
    @include('client.components.menu_main')
@endsection

@section('content')
    <div class="content-padding">
        <h1>Vyhledávání</h1>

        <form method="POST" action="{{route('client.search')}}">
            @csrf
            <input class="form-control search-basic" name="query"
                   placeholder="Zadejte název písně, část textu nebo jméno autora" value="{{$phrase}}" autofocus>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </form>

        @if(isset($phrase))
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
                            <a href="{{route('client.author',$author)}}">{{$author->getSearchTitle()}}</a>
                            - {{$author->getSearchText()}}
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
    @endif
@endsection
