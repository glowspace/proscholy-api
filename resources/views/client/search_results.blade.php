@extends('layout.client')

@section('navbar')
    @include('client.components.menu_main')
@endsection

@section('content')
<v-app>
    <div class="content-padding">
        <h1>Vyhledávání</h1>

        {{-- <form method="POST"
              action="{{route('client.search')}}">
            @csrf
            <input class="form-control search-basic"
                   name="query"
                   placeholder="Zadejte název písně, část textu nebo jméno autora"
                   value="{{$phrase}}"
                   autofocus>
            <button type="submit"
                    class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </form> --}}
        <search-input v-bind:str="abc"></search-input>

        @if(isset($phrase))
            <div class="row">
                <div class="col-sm-8">
                    <div class="card card-blue">
                        <div class="card-header">Písně</div>
                        <div class="card-body">
                            <table class="table">
                                @forelse($song_lyrics as $song_lyric)
                                    <tr>
                                        <td style="width: 15px"><i class="fas fa-music"></i></td>
                                        <td>
                                            <a href="{{ $song_lyric->public_url }}">{{$song_lyric->getSearchTitle()}}</a>
                                        </td>

                                        <td style="width: 10px;"
                                            class="no-left-padding">
                                            @if($song_lyric->spotifyTracks()->count())
                                                <i class="fab fa-spotify text-success"></i>
                                            @else
                                                <i class="fab fa-spotify text-very-muted"></i>
                                            @endif
                                        </td>
                                        <td style="width: 10px;"
                                            class="no-left-padding">
                                            @if($song_lyric->soundcloudTracks()->count())
                                                <i class="fab fa-soundcloud"
                                                   style="color: orangered;"></i>
                                            @else
                                                <i class="fab fa-soundcloud text-very-muted"></i>
                                            @endif
                                        </td>
                                        <td style="width: 10px;"
                                            class="no-left-padding">
                                            @if($song_lyric->scoreExternals()->count() || $song_lyric->scoreFiles()->count())
                                                <i class="fa fa-file-pdf"
                                                   style="color: #3961ad"></i>
                                            @else
                                                <i class="fa fa-file-pdf text-very-muted"
                                                   style="color: #3961ad"></i>
                                            @endif
                                        </td>
                                        <td style="width: 10px;"
                                            class="no-left-padding">
                                            @if($song_lyric->youtubeVideos()->count())
                                                <i class="fab fa-youtube text-danger"></i>
                                            @else
                                                <i class="fab fa-youtube text-very-muted"></i>
                                            @endif
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

                    <div class="card card-green">
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
                </div>

                <div class="col-sm-4">
                    <div class="card card-red">
                        <div class="card-header">Možnosti vyhledávání</div>
                        <div class="card-body">
                            <div class="song-tags">
                                @foreach ($tags->where('type', 1) as $tag)
                                    <a class="tag tag-blue">{{$tag->name}}</a>
                                @endforeach
                            </div>

                            <br>

                            <div class="song-tags">
                                @foreach ($tags->where('type', 0)->where('parent_tag_id', null) as $tag)
                                    <a class="tag tag-green">{{$tag->name}}</a>

                                    @foreach($tag->child_tags as $child_tag)
                                        <a class="tag tag-yellow">{{$child_tag->name}}</a>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</v-app>
@endsection
