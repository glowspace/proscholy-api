@extends('layout.client-sidepage')

@section('title', $song_l->name . ' - píseň ve zpěvníku ProScholy.cz')

@section('content')
    <div class="container">
        <h1>{{$song_l->name}}</h1>
        <div class="row {{ $reversed_columns ? "flex-row-reverse" : ""}}">
            <div class="{{ $reversed_columns ? "col-lg-5 " : "col-lg-8" }}">
                <div class="card" id="cardLyrics">
                    <div class="card-header d-flex flex-row justify-content-between flex-wrap" style="padding: 8px;">
                        <div class="p-2">
                            @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent
                        </div>
                        <div class="d-flex flex-column p-2">
                            @if ($song_l->lyrics)
                                <transposition></transposition>
                                <font-sizer></font-sizer>
                            @endif
                        </div>
                    </div>
                    <div class="card-body"  style="border-bottom: #7f97ab">
                        <div class="d-flex flex-column flex-sm-row-reverse mb-2">
                            <div class="song-tags d-flex flex-sm-column align-items-sm-end mb-2">
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
                            <div class="flex-grow-1">
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

{{--                    <div class="card-text" style="border-bottom: 1px #d6d6d6 solid"></div>--}}

                    <div class="card-footer" style="background-color: #3961ad12">
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