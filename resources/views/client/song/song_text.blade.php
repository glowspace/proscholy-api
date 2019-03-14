@extends('layout.master')

@section('title', $song_l->name . ' - píseň ve zpěvníku ProScholy.cz')

@section('navbar')
    @include('client.components.menu_song')
@endsection

@section('content')
    <div class="content-padding">
        <div class="row">
            <div class="col-lg-8">
                <h1>{{$song_l->name}}</h1>

                <div class="card" id="cardLyrics">
                    <div class="card-header" style="padding: 8px;">
                        <span style="display: inline-block; padding: 10px;">@component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent</span>
                        <transposition></transposition>
                        {{-- <div class="transpose-control-wrapper" style="display: inline-block">
                            <span>Transpozice: </span><a class="btn btn-secondary" id="transposeUp">+1</a>
                            <a class="btn btn-secondary" id="transposeDown">-1</a>
                        </div> --}}
                    </div>
                    <div class="card-body">
                        @if($song_l->lyrics)
                            <div class="song-component">
                                {!! $song_l->formatted_lyrics !!}
                            </div>
                        @else
                            <div class="song-component">Text písně připravujeme.</div>
                            @if ($song_l->scoreExternals()->count() + $song_l->scoreFiles()->count() > 0)
                                <br><div><b>V nabídce vlevo jsou k nahlédnutí dostupné materiály ke stažení.</b></div>
                            @endif
                        @endif
                        <hr>
                        Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20px"> {{date('Y')}}
                    </div>
                </div>
            </div>
            <div class="col-lg-4 content-padding-top">
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