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
            <div class="card">
                <div class="card-header">Písně</div>
                <div class="card-body">
                    <table class="table">
                        @forelse($song_lyrics as $song_lyric)
                            <tr>
                                <td width="20"><i class="fas fa-music"></i></td>
                                <td>
                                    <a href="{{ $song_lyric->public_url }}">{{$song_lyric->getSearchTitle()}}</a>
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
                </div>
            </div>

            <div class="card">
                <div class="card-header">Autoři</div>
                <div class="card-body">
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
            </div>
        @endif
    </div>
@endsection
