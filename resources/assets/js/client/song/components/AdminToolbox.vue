<template>
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
</template>

<script>
    /**
     * TODO: show only when admin authenticated
     */
    export default {
        name: "AdminToolbox",

        props: ['song'],
    }
</script>

<style scoped>

</style>
