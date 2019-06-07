@extends('layout.client-sidepage')

@section('title', $song_l->name . ' - píseň ve zpěvníku ProScholy.cz')

@section('content')
    <div class="container">
        <h1>{{$song_l->name}}</h1>
        <div class="row {{ $reversed_columns ? "flex-row-reverse" : ""}}">
            <div class="{{ $reversed_columns ? "col-lg-5 " : "col-lg-12 px-0" }}">
                <div class="card {{ $reversed_columns ? "" : "mb-0 mb-sm-4" }}" id="cardLyrics">
                    <div class="card-header d-flex flex-row justify-content-between flex-wrap px-3 py-2">
                        <div>
                            @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent
                        </div>
                        <div class="song-tags d-flex flex-row align-items-start">
                            @foreach ($tags_officials as $tag)
                                <a class="tag tag-blue">{{ $tag->name }}</a>
                            @endforeach
                            @foreach ($tags_unofficials as $tag)
                                @if ($tag->parent_tag == null)
                                    <a class="tag tag-green">{{ $tag->name }}</a>
                                @else
                                    <a class="tag tag-yellow">{{ $tag->name }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="card-header p-1 song-links">
                        <a class="btn btn-secondary"><i class="fas fa-file-alt"></i> Noty</a>
                        {{-- <a class="btn btn-secondary"><i class="fas fa-file"></i> Další soubory</a> --}}
                        <a class="btn btn-secondary"><i class="fas fa-language"></i> Překlady</a>
                        <a class="btn btn-secondary"><i class="fas fa-file-pdf"></i> Stáhnout</a>
                        <a class="btn btn-secondary float-right"><i class="fas fa-exclamation-triangle p-0"></i></a>
                    </div>
                    <div class="card-body p-0"  style="border-bottom: #7f97ab">
                        <div class="d-flex flex-column flex-row-reverse mb-2">
                            <div class="d-flex flex-column p-1">
                                <a class="btn btn-secondary m-0"><i class="fas fa-expand"></i></a>
                                <a class="btn btn-secondary m-0"><i class="fas fa-columns"></i></a>
                                <a class="btn btn-secondary m-0"><i class="fas fa-sun"></i></a>
                            </div>
                            <div class="flex-grow-1 px-3 py-2">
                                @if($song_l->lyrics)
                                    {!! $song_l->getFormattedLyrics() !!}
                                @else
                                    <p>Text písně připravujeme.</p>
                                    @if ($song_l->scoreExternals()->count() + $song_l->scoreFiles()->count() > 0)
                                        <p><b>V nabídce vlevo jsou k nahlédnutí všechny materiály ke stažení.</b></p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
					@if ($song_l->lyrics)
						<controls></controls>
					@endif

{{--                    <div class="card-text" style="border-bottom: 1px #d6d6d6 solid"></div>--}}

                    <div class="card-footer" style="background-color: #3961ad12">
                        Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20px"> {{date('Y')}}
                    </div>
                </div>
            </div>
            <div class="{{ $reversed_columns ? "col-lg-7" : "col-lg-4 d-none" }}">
                @if($song_l->scoreFiles()->count() > 0)
                    {{-- @component('client.components.thumbnail_preview', ['instance' => $song_l->scoreFiles()->first()])@endcomponent --}}
                    @component('client.components.media_widget', ['source' => $song_l->scoreFiles()->first()])@endcomponent
                @elseif ($song_l->scoreExternals()->count() > 0)
                    @component('client.components.media_widget', ['source' => $song_l->scoreExternals()->first()])@endcomponent
                @endif

                @if($song_l->youtubeVideos()->count() > 0)
                    @component('client.components.media_widget', ['source' => $song_l->youtubeVideos()->first()])@endcomponent
                @endif

                @if($song_l->spotifyTracks()->count() > 0)
                    @component('client.components.media_widget', ['source' => $song_l->spotifyTracks()->first()])@endcomponent
                @endif

                @if($song_l->soundcloudTracks()->count() > 0)
                    @component('client.components.media_widget', ['source' => $song_l->soundcloudTracks()->first()])@endcomponent
                @endif

                @if($song_l->audioFiles()->count() > 0)
                    @component('client.components.media_widget', ['source' => $song_l->audioFiles()->first()])@endcomponent
                @endif
            </div>
        </div>
    </div>
@endsection