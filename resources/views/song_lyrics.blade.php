@extends('layout')

@section('content')
    <h2 style="margin-bottom: 5px;">{{$song_l->name}}</h2>
    @if(! $song_l->is_original)
        @if($song_l->is_authorized)
            autorizovaný překlad písně <a
                    href="{{$song_l->song->getOriginalLyric()->getLink()}}">{{$song_l->song->getOriginalLyric()->name}}</a>
        @else
            překlad písně <a
                    href="{{$song_l->song->getOriginalLyric()->getLink()}}">{{$song_l->song->getOriginalLyric()->name}}</a>
        @endif
        <br>
    @endif

    @if($song_l->is_original)
        @if($song_l->authors()->count() == 0)
            <i>neznámý autor</i>
        @elseif($song_l->authors()->count() == 1)
            autor písně: <a
                    href="{{route('author.single', ['id'=> $song_l->authors->first()->id])}}">{{$song_l->authors->first()->name}}</a>
        @else
            autoři písně:<br>
            @foreach($song_l->authors as $author)
                <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
            @endforeach
        @endif
    @else
        @if($song_l->authors()->count() == 0)
            <i>neznámý autor</i>
        @elseif($song_l->authors()->count() == 1)
            autor překladu: <a
                    href="{{route('author.single', ['id'=> $song_l->authors->first()->id])}}">{{$song_l->authors->first()->name}}</a>
        @else
            autoři překladu:<br>
            @foreach($song_l->authors as $author)
                <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
            @endforeach
        @endif
    @endif

    <br>
    <div id="lyrics">{{$song_l->lyrics}}</div>

    @if($song_l->videos()->count() == 1)
        <h4>Video</h4>

        <div class="row">
            @foreach($song_l->videos as $video)
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    {!! $video->getHtml() !!}
                </div>
            @endforeach
        </div>
    @elseif($song_l->videos()->count() > 1)
        <h4>Videa</h4>

        <div class="row">
            @foreach($song_l->videos as $video)
                <div class="col-sm-4">
                    {!! $video->getHtml() !!}
                </div>
            @endforeach
        </div>
    @endif
@endsection

@section('scripts')
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>

    @include('scripts.chordpro_parse')

    <script>
        $(document).ready(function () {
            let lyrics = document.getElementById('lyrics');
            let lyrics_source = document.getElementById('lyrics').innerHTML;

            lyrics.innerHTML = parseChordPro(lyrics_source, 0);
        });
    </script>


@endsection
