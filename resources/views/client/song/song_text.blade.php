@extends('layout.master')

@section('title', $song_l->name . ' - píseň ve zpěvníku ProScholy.cz')

@section('navbar')
    @include('client.components.menu_song')
@endsection

@section('content')
    <div class="content-padding">
        <h1>{{$song_l->name}}</h1>
        <div class="row {{ $reversed_columns ? "flex-row-reverse" : ""}}">
            <div class="{{ $reversed_columns ? "col-lg-5 " : "col-lg-8" }}">
                <div class="card" id="cardLyrics">
                    <div class="card-header" style="padding: 8px;">
                        <span style="display: inline-block; padding: 10px;">@component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent</span>
                        <transposition></transposition>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column flex-sm-row-reverse">
                            <div class="song-tags d-flex flex-sm-column align-items-sm-end mb-2">
                                {{-- <a href="#" class="tag">štítek 1</a>
                                <a href="#" class="tag">štítek 2</a>
                                <a href="#" class="tag">štítek 3</a> --}}
                                @foreach ($tags as $tag)
                                    <a href="#" class="tag">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                            <div class="flex-grow-1">
                                @if($song_l->lyrics)
                                    {!! $song_l->formatted_lyrics !!}
                                @else
                                    <p>Text písně připravujeme.</p>
                                    @if ($song_l->scoreExternals()->count() + $song_l->scoreFiles()->count() > 0)
                                        <p><b>V nabídce vlevo jsou k nahlédnutí dostupné materiály ke stažení.</b></p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        {{-- <div class="song-component">
                            
                            <div class="song-component-tags">
                                <div class="tag tag--official">štítek</div>
                            </div>
                        </div> --}}
                        <hr>
                        Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20px"> {{date('Y')}}
                    </div>
                </div>
            </div>
            <div class="{{ $reversed_columns ? "col-lg-7" : "col-lg-4" }}">
                @if($song_l->description)
                    <div class="card">
                        <div class="card-header">Informace o písni</div>
                        <div class="card-body">
                            <b>Autor</b>
                        </div>
                    </div>
                @endif

                @if($song_l->scoreFiles()->count() > 0)
                    @component('client.components.thumbnail_preview', ['instance' => $song_l->scoreFiles()->first()])@endcomponent
                @elseif ($song_l->scoreExternals()->count() > 0)
                    @component('client.components.thumbnail_preview', ['instance' => $song_l->scoreExternals()->first()])@endcomponent
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