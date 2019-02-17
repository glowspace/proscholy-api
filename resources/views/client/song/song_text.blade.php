@extends('layout.master')

@section('navbar')
    @include('client.components.menu_song')
@endsection

@section('content')
    <div class="content-padding">
        <div class="row">
            <div class="col-sm-8">
                <h1>{{$song_l->name}}</h1>

                <div class="card" id="cardLyrics" style="display: none">
                    <div class="card-header" style="padding: 8px;">
                        <span style="display: inline-block; padding: 10px;">@component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent</span>

                        <div class="transpose-control-wrapper" style="display: inline-block">
                            <span>Transpozice: </span><a class="btn btn-secondary" id="transposeUp">+1</a>
                            <a class="btn btn-secondary" id="transposeDown">-1</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="lyrics">{!!$song_l->lyrics !!}</div>

                        <hr>
                        Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20px"> {{date('Y')}}
                    </div>
                </div>
            </div>
            <div class="col-sm-4 content-padding-top">
                @if($song_l->description)
                    <div class="card">
                        <div class="card-header">Informace o písni</div>
                        <div class="card-body">
                            <b>Autor</b>
                        </div>
                    </div>
                @endif

                @if($song_l->youtubeVideos()->count() > 0)
                    @component('client.components.external_embed', ['external' => $song_l->youtubeVideos()->first()])@endcomponent
                @endif

                @if($song_l->spotifyTracks()->count() > 0)
                    @component('client.components.external_embed', ['external' => $song_l->spotifyTracks()->first()])@endcomponent
                @endif

                @if($song_l->soundcloudTracks()->count() > 0)
                    @component('client.components.external_embed', ['external' => $song_l->soundcloudTracks()->first()])@endcomponent
                @endif
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>

    @include('scripts.chordpro_parse')

    <script>
        lyrics_source = document.getElementById('lyrics').innerHTML;
        lyrics = document.getElementById('lyrics');
        current = 0;

        $(document).ready(function () {
            lyrics.innerHTML = parseChordPro(lyrics_source, 0);

            // Lyrics fade in animation
            $("#cardLyrics").fadeIn("slow", function () {

            });
        });

        $("#transposeUp").click(function () {
            current = current + 1;
            lyrics.innerHTML = parseChordPro(lyrics_source, current);
        })

        $("#transposeDown").click(function () {
            current = current - 1;
            lyrics.innerHTML = parseChordPro(lyrics_source, current);
        })
    </script>
@endpush
