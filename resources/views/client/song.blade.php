@extends('layout.client')

@section('title', $song_l->name . ' – píseň ve zpěvníku ProScholy.cz')
@push('meta_tags')
<meta name="description" content="Píseň {{ $song_l->name }}, autoři: {{ $song_l->authors->implode(', ') }}">
@endpush

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-lg-row flex-wrap flex-lg-nowrap mt-4">
            <div class="flex-grow-1">
                <h1 class="song-title">{{$song_l->name}}</h1>
                <h4 class="song-number">{{ $song_l->id }}</h4>
                <p class="song-author"> @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent</p>
            </div>
            <div class="song-tags align-self-end" style="flex: 4;">
                <div class="d-flex flex-row flex-wrap align-items-start justify-content-md-end mb-3">
                    @foreach ($tags_officials as $tag)
                        <a class="tag tag-blue">{{ $tag->name }}</a>
                    @endforeach
                    @foreach ($tags_unofficials as $tag)
                        @if ($tag->parent_tag == null)
                            <a class="tag tag-green">{{ $tag->name }}</a>
                        @else
                            {{-- do not display the parent tag as for now --}}
                            {{-- <a class="tag tag-green">{{ $tag->name }}</a> --}}
                        @endif
                    @endforeach
                </div>
                <div class="d-flex flex-row flex-wrap align-items-start justify-content-md-end mb-3">
                    @foreach ($songbook_records as $record)
                        <a class="tag tag-yellow">{{ $record->name . " " . $record->pivot->number }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <song-view
            song-id="{{$song_l->id}}"
            render-media="{{ ($song_l->youtubeVideos()->count() + $song_l->spotifyTracks()->count() + $song_l->soundcloudTracks()->count() + $song_l->audioFiles()->count())?true:false }}"
            render-scores="{{ ($song_l->scoresCount())?true:false }}"
            render-translations="{{ ($song_l->song->song_lyrics()->count() > 1)?true:false }}"
            >
            {!! $song_l->getFormattedLyrics() !!}
            <template v-slot:score>
                @if($song_l->scoreFiles()->count() + $song_l->scoreExternals()->count())
                <div class="card-header media-opener py-2 rounded">
                    <i class="fas fa-file-alt"></i>
                    Zobrazit notové zápisy
                </div>
                @endif
            </template>
            <template v-slot:media>
                @if($song_l->youtubeVideos()->count() + $song_l->spotifyTracks()->count() + $song_l->soundcloudTracks()->count() + $song_l->audioFiles()->count())
                    <div class="card-header media-opener py-2">
                        <i class="fas fa-headphones"></i>
                        Dostupné nahrávky a videa
                    </div>
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
                @endif
            </template>
        </song-view>

        <div class="row" id="preloadPlaceholder">
            <div class="col-lg-9">
                <div class="card card-lyrics">
                <div class="card-header p-1 song-links">
                    <a class="btn btn-secondary">
                        <i class="fas fa-file-alt"></i>
                        <span class="d-none d-sm-inline">Noty</span>
                    </a>
                    <a class="btn btn-secondary">
                        <i class="fas fa-language"></i>
                        <span class="d-none d-sm-inline">Překlady</span>
                    </a>
                    <!--googleoff: all-->
                    <a class="btn btn-secondary">
                        <i class="fas fa-file-pdf"></i>
                        <span class="d-none d-sm-inline">Export</span>
                    </a>
                    <!--googleon: all-->
                </div>
                <div class="card-body py-2">
                    {!! $song_l->getFormattedLyrics() !!}
                </div>
                <div class="controls p-1"></div>
                <div class="card-footer p-1 song-links">
                    <!-- todo: asset img -->
                    <div class="px-3 py-2 d-inline-block">
                        Zpěvník ProScholy.cz
                        <img src="/img/logo_v2.png" width="20px">
                        {{ now()->year }}
                    </div>
                </div>
                </div>
            </div>
        </div>

        @if (Auth::check())
        <div class="admin-controls d-none d-sm-block">
            <a class="btn btn-secondary" href="{{route('admin.dashboard')}}">
                <i class="fas fa-columns"></i>
            </a>
            <a class="btn btn-secondary" href="#">Nástěnka</a>
            <br>

            @if (App\SongLyric::restricted()->where('id', $song_l->id)->count() > 0)
                <a class="btn btn-secondary" href="{{route('admin.song.edit', ['song_lyric' => $song_l->id])}}">
                    <i class="fas fa-edit"></i>
                </a>
                <a class="btn btn-secondary" href="#">Upravit písničku</a>
                <br>

                <a class="btn btn-secondary" href="{{route('admin.external.create_for_song', ['song_lyric' => $song_l->id])}}">
                    <i class="fas fa-link"></i>
                </a>
                <a class="btn btn-secondary" href="#">Přidat odkaz</a>
                <br>

                <a class="btn btn-secondary" href="{{route('admin.file.create_for_song', ['song_lyric' => $song_l->id])}}">
                    <i class="fas fa-file"></i>
                </a>
                <a class="btn btn-secondary" href="#">Nahrát soubor</a>
            </div>
            @endif
        </div>
        @endif
    </div>
@endsection