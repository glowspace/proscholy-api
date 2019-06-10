@extends('layout.client-sidepage')

@section('title', $song_l->name . ' – píseň ve zpěvníku ProScholy.cz')

@section('content')
    <div class="container">
        <div class="d-flex flex-md-row justify-content-between flex-wrap mt-4">
            <div>
                <h1 class="m-0 pb-0">{{$song_l->name}}</h1>
                <p class="song-author"> @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent</p>
            </div>
            <div class="song-tags d-flex flex-row flex-wrap align-items-start justify-content-md-end mb-3">
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
        <div class="row {{ $reversed_columns ? "flex-row-reverse" : ""}}">
            <div class="{{ $reversed_columns ? "col-lg-5 " : "col-lg-9" }}">
                <div class="card card-lyrics {{ $reversed_columns ? "" : "mb-0 mb-sm-4" }}" id="cardLyrics">
                    <div class="card-header p-1 song-links">
                        <a class="btn btn-secondary"><i class="fas fa-file-alt"></i> <span class="d-none d-sm-inline">Noty</span></a>
                        {{-- <a class="btn btn-secondary"><i class="fas fa-file"></i> <span class="d-none d-sm-inline">Další soubory</span></a> --}}
                        <a class="btn btn-secondary"><i class="fas fa-language"></i> <span class="d-none d-sm-inline">Překlady</span></a>
                        <a class="btn btn-secondary"><i class="fas fa-file-pdf"></i> <span class="d-none d-sm-inline">Export</span></a>
                        <a class="btn btn-secondary float-right"><i class="fas fa-exclamation-triangle"></i></a>
                    </div>
                    <div class="card-body py-2">
                        <div class="d-flex flex-row-reverse justify-content-between">
                            <right-controls></right-controls>
                            <div id="song-lyrics" style="overflow: hidden">
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
                    <controls>
                        <template v-slot:media>
                            @if($song_l->youtubeVideos()->count() > 0 || $song_l->spotifyTracks()->count() > 0 || $song_l->soundcloudTracks()->count() > 0 || $song_l->audioFiles()->count() > 0)
                                <div class="row pt-2">
                                @if($song_l->spotifyTracks()->count() > 0)
                                    @foreach($song_l->spotifyTracks as $external)
                                    <div class="col-md-6">
                                        @component('client.components.media_widget', ['source' => $external])@endcomponent
                                    </div>
                                    @endforeach
                                @endif

                                @if($song_l->soundcloudTracks()->count() > 0)
                                    @foreach($song_l->soundcloudTracks as $external)
                                    <div class="col-md-6">
                                        @component('client.components.media_widget', ['source' => $external])@endcomponent
                                    </div>
                                    @endforeach
                                @endif

                                @if($song_l->audioFiles()->count() > 0)
                                    @foreach($song_l->audioFiles as $external)
                                    <div class="col-md-6">
                                        @component('client.components.media_widget', ['source' => $external])@endcomponent
                                    </div>
                                    @endforeach
                                @endif

                                @if($song_l->youtubeVideos()->count() > 0)
                                    @foreach($song_l->youtubeVideos as $external)
                                    <div class="col-md-6">
                                        @component('client.components.media_widget', ['source' => $external])@endcomponent
                                    </div>
                                    @endforeach
                                @endif
                                </div>
                            @endif
                        </template>
                    </controls>

                    <div class="card-footer">
                        Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20px"> {{date('Y')}}
                    </div>
                </div>
            </div>
            <div class="{{ $reversed_columns ? "col-lg-7" : "col-lg-3" }}">
                @if($song_l->scoreFiles()->count() > 0)
                    {{-- @component('client.components.thumbnail_preview', ['instance' => $song_l->scoreFiles()->first()])@endcomponent --}}
                    @component('client.components.media_widget', ['source' => $song_l->scoreFiles()->first()])@endcomponent
                @elseif ($song_l->scoreExternals()->count() > 0)
                    @component('client.components.media_widget', ['source' => $song_l->scoreExternals()->first()])@endcomponent
                @endif

                @if($song_l->youtubeVideos()->count() > 0 || $song_l->spotifyTracks()->count() > 0 || $song_l->soundcloudTracks()->count() > 0 || $song_l->audioFiles()->count() > 0)
                    <media-opener>
                    @if($song_l->spotifyTracks()->count() > 0)
                    <div class="media-opener"><i class="fab fa-spotify text-success"></i> Spotify</div>
                    @endif

                    @if($song_l->soundcloudTracks()->count() > 0)
                    <div class="media-opener"><i class="fab fa-soundcloud" style="color: orangered;"></i> SoundCloud</div>
                    @endif

                    @if($song_l->audioFiles()->count() > 0)
                    <div class="media-opener"><i class="fas fa-music"></i> MP3</div>
                    @endif

                    @if($song_l->youtubeVideos()->count() > 0)
                    <div class="media-opener"><i class="fab fa-youtube text-danger"></i> YouTube</div>
                    @endif
                    </media-opener>
                @endif
            </div>
        </div>
    </div>
@endsection