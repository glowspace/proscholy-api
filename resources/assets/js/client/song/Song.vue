<template>
    <div class="container">
        <div class="mt-4 mb-3">
            <div>
                <h1 class="song-title">{{ song.name}}</h1>
                <div class="d-flex align-items-center mt-1">
                    <h4 class="song-number m-0">{{ song.id }}</h4>
                    <p class="song-author ml-3 mb-0">
                        <song-author-label :song="song"></song-author-label>
                    </p>
                </div>
            </div>
            <div class="song-tags p-0 mt-3">
                <div class="d-inline-flex flex-row flex-wrap align-items-start mr-3"
                     v-if="tags.officials.length && tags.unofficials.length">
                    @foreach ($tags_officials as $tag)
                    <a class="tag tag-blue"
                       href="{{route("
                       client.search_results")}}?searchString=&tags={{ $tag->id }}&langs=&songbooks=">{{ $tag->name
                    }}</a>
                    @endforeach
                    @foreach ($tags_unofficials as $tag)
                    @if ($tag->parent_tag == null)
                    {{-- do not display the parent tag as for now --}}
                    {{-- <a class="tag tag-green">{{ $tag->name }}</a> --}}
                    @else
                    <a class="tag tag-green"
                       href="{{route("
                       client.search_results")}}?searchString=&tags={{ $tag->id }}&langs=&songbooks=">{{ $tag->name
                    }}</a>
                    @endif
                    @endforeach
                </div>

                <!-- Liturgy ČBK approval -->
                <div class="d-inline-flex flex-row flex-wrap align-items-start mr-3"
                     v-if="song.liturgy_approval_status">
                    <a class="tag tag-blue">{{song.liturgy_approval_status_string_values[song.liturgy_approval_status]}}
                        <i class="fas fa-check"></i></a>
                </div>

                <div class="d-inline-flex flex-row flex-wrap align-items-start"
                     v-if="songbook_records">
                    @foreach ($songbook_records as $record)
                    {{-- <a class="tag tag-yellow"
                            title="{{ $record->name }}"
                            href="{{route("
                            client.search_results")}}?searchString=&tags=&langs=&songbooks={{ $record->id }}">{{
                    $record->name . ' ' . $record->pivot->number }}</a> --}}
                    <a class="tag tag-yellow songbook-tag"
                       title="{{ $record->name }}"
                       href="{{route("
                       client.search_results")}}?searchString=&tags=&langs=&songbooks={{ $record->id
                    }}"><span class="songbook-name">{{ $record->name }}</span><span class="songbook-number">{{ $record->pivot->number }}</span></a>
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
                @if($song_l->youtubeVideos()->count() + $song_l->spotifyTracks()->count() +
                $song_l->soundcloudTracks()->count() + $song_l->audioFiles()->count())
                <div class="card-header media-opener py-2">
                    <i class="fas fa-headphones"></i>
                    Dostupné nahrávky<span class="d-none d-xl-inline"> a videa</span>
                </div>
                @if($song_l->spotifyTracks()->count() > 0)
                <div class="media-opener"><i class="fab fa-spotify text-success"></i> Spotify</div>
                @endif

                @if($song_l->soundcloudTracks()->count() > 0)
                <div class="media-opener"><i class="fab fa-soundcloud"
                                             style="color: orangered;"></i> SoundCloud
                </div>
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

        <div class="row"
             id="preloadPlaceholder">
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
                        <div class="px-3 py-2 d-inline-block">
                            Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}"
                                                      width="20"> {{date('Y')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (Auth::check())
        <div class="admin-controls d-none d-sm-block">
            <a class="btn btn-secondary"
               href="{{route('admin.dashboard')}}">
                <i class="fas fa-columns"></i>
            </a>
            <a class="btn btn-secondary"
               href="#">Nástěnka</a>
            <br>

            @if (App\SongLyric::restricted()->where('id', $song_l->id)->count() > 0)
            <a class="btn btn-secondary"
               href="{{route('admin.song.edit', ['song_lyric' => $song_l->id])}}">
                <i class="fas fa-edit"></i>
            </a>
            <a class="btn btn-secondary"
               href="#">Upravit písničku</a>
            <br>

            <a class="btn btn-secondary"
               href="{{route('admin.external.create_for_song', ['song_lyric' => $song_l->id])}}">
                <i class="fas fa-link"></i>
            </a>
            <a class="btn btn-secondary"
               href="#">Přidat odkaz</a>
            <br>

            <a class="btn btn-secondary"
               href="{{route('admin.file.create_for_song', ['song_lyric' => $song_l->id])}}">
                <i class="fas fa-file"></i>
            </a>
            <a class="btn btn-secondary"
               href="#">Nahrát soubor</a>
        </div>
        @endif
    </div>
    @endif
    </div>
</template>

<script>
    import SongAuthorLabel from "./components/SongAuthorLabel";

    export default {
        name: "Song",
        components: {SongAuthorLabel}
    }
</script>

<style scoped>

</style>
