<a class="btn btn-secondary" href="{{route('client.home')}}">
    <i class="fas fa-home"></i> Úvod
</a><a class="btn btn-secondary" href="{{route('client.search_results', '')}}">
    <i class="fas fa-search"></i> Vyhledat
</a>

<div class="navbar-label material-shadow text-success">Materiály</div>

<a class="btn btn-secondary" href="{{route('client.song.text', $song_l)}}">
    <i class="fas fa-align-left"></i> Text + akordy

</a>

@if($song_l->scoresCount())
    <a class="btn btn-secondary" href="{{route('client.song.score', $song_l)}}">
        <i class="fas fa-music"></i> Noty
        <span class="badge badge-pill">{{$song_l->scoresCount()}}</span>
    </a>
@endif

@if($song_l->song->song_lyrics()->count() > 1)
    <a class="btn btn-secondary" href="{{route('client.song.translations', $song_l)}}">
        <i class="fas fa-language"></i> Překlady
        <span class="badge badge-pill">{{$song_l->song->song_lyrics()->count()}}</span>
    </a>
@endif

@if($song_l->audioTracks()->count())
    <a class="btn btn-secondary" href="{{route('client.song.audio_records', $song_l)}}">
        <i class="fas fa-microphone"></i> Nahrávky
        <span class="badge badge-pill">{{$song_l->audioTracks()->count()}}</span>
    </a>
@endif

@if($song_l->youtubeVideos->count())
    <a class="btn btn-secondary" href="{{route('client.song.videos', $song_l)}}">
        <i class="fab fa-youtube"></i> Videa
        <span class="badge badge-pill">{{$song_l->youtubeVideos->count()}}</span>
    </a>
@endif

{{-- if a user is logged in --}}
@if (Auth::check())
    <div class="navbar-label material-shadow text-success">Administrace</div>

    <a class="btn btn-secondary" href="{{route('admin.song.edit', ['song_lyric' => $song_l->id])}}">
        <i class="fas fa-microphone"></i> Upravit písničku
    </a>

    <a class="btn btn-secondary" href="{{route('admin.external.create_for_song', ['song_lyric' => $song_l->id])}}">
        <i class="fas fa-microphone"></i> Přidat odkaz
    </a>
@endif

{{--<div class="navbar-label material-shadow text-warning">Možnosti</div>--}}

{{--<a class="btn btn-secondary">--}}
{{--<i class="fas fa-upload"></i> Přidat materiál--}}
{{--</a>--}}

{{--<a class="btn btn-secondary">--}}
{{--<i class="fas fa-exclamation-circle"></i> Nahlásit--}}
{{--</a>--}}