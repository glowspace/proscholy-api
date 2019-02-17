<a class="btn btn-secondary" href="{{route('client.home')}}">
    <i class="fas fa-home"></i> Úvod
</a><a class="btn btn-secondary" href="{{route('client.search_results', '')}}">
    <i class="fas fa-search"></i> Vyhledat
</a>

<div class="navbar-label material-shadow text-success">Materiály</div>

<a class="btn btn-secondary" href="{{route('client.song.text', $song_l)}}">
    <i class="fas fa-align-left"></i> Text + akordy

</a>
<a class="btn btn-secondary">
    <i class="fas fa-music"></i> Noty
    <span class="badge badge-pill">2</span>
</a>
<a class="btn btn-secondary">
    <i class="fas fa-language" href="{{route('client.song.translations', $song_l)}}"></i> Překlady
    <span class="badge badge-pill">{{$song_l->song->song_lyrics()->count()}}</span>
</a>

@if($song_l->audioTracks()->count() > 0)
    <a class="btn btn-secondary" href="{{route('client.song.audio_records', $song_l)}}">
        <i class="fas fa-microphone"></i> Nahrávky
        <span class="badge badge-pill">{{$song_l->audioTracks()->count()}}</span>
    </a>
@endif

@if($song_l->youtubeVideos->count() > 0)
    <a class="btn btn-secondary" href="{{route('client.song.videos', $song_l)}}">
        <i class="fab fa-youtube"></i> Videa
        <span class="badge badge-pill">{{$song_l->youtubeVideos->count()}}</span>
    </a>
@endif

<div class="navbar-label material-shadow text-warning">Možnosti</div>

<a class="btn btn-secondary">
    <i class="fas fa-upload"></i> Přidat materiál
</a>

<a class="btn btn-secondary">
    <i class="fas fa-exclamation-circle"></i> Nahlásit
</a>