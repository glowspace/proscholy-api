<template>
    <div class="container">
        <div class="mt-4 mb-3">
            <div>
                <h1 class="song-title">{{ song.name }}</h1>
                <div class="d-flex align-items-center mt-1">
                    <h4 class="song-number m-0">{{ song.id }}</h4>
                    <p class="song-author ml-3 mb-0">
                        <song-author-label :song="song"></song-author-label>
                    </p>
                </div>
            </div>

            <!--tags :song="song"/ -->
        </div>

        <song-view
            :song_lyric="song"
            :render-media="false"
            :render-scores="false"
            :render-translations="false"
        >

            {{ song.getFormattedLyrics }}

            <template v-slot:score>
                <div class="card-header media-opener py-2 rounded"
                     v-if="song.scoreFiles.length + song.scoreExternals.length">
                    <i class="fas fa-file-alt"></i>
                    Zobrazit notové zápisy
                </div>
            </template>

            <template v-slot:media>
                <div v-if="song.youtubeVideos.length + song.spotifyTracks.length +
                    song.soundcloudTracks.length + song.audioFiles.length">

                    <div class="card-header media-opener py-2">
                        <i class="fas fa-headphones"></i>
                        Dostupné nahrávky<span class="d-none d-xl-inline"> a videa</span>
                    </div>

                    <div class="media-opener"
                         v-if="song.spotifyTracks.length">
                        <i class="fab fa-spotify text-success"></i> Spotify
                    </div>

                    <div class="media-opener"
                         v-if="song.soundcloudTracks.length">
                        <i class="fab fa-soundcloud"
                           style="color: orangered;"></i> SoundCloud
                    </div>

                    <div class="media-opener"
                         v-if="song.audioFiles.length">
                        <i class="fas fa-music"></i> MP3
                    </div>

                    <div class="media-opener"
                         v-if="song.youtubeVideos.length">
                        <i class="fab fa-youtube text-danger"></i> YouTube
                    </div>
                </div>
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
                        {{ song.getFormattedLyrics }}
                    </div>
                    <div class="controls p-1"></div>
                    <div class="card-footer p-1 song-links">
                        <div class="px-3 py-2 d-inline-block">
                            Zpěvník ProScholy.cz <img src="/img/logo_v2.png"
                                                      width="20"
                                                      alt="Logo zpěvníku"> 2020
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- admin-toolbox :song="song" v-if="false"></admin-toolbox -->
    </div>
</template>

<script>
    import SongAuthorLabel from "./components/SongAuthorLabel";
    import SongView from "./components/SongBox/SongBox";

    export default {
        name: "SongDetail",

        components: {
            SongView,
            SongAuthorLabel
        },

        props: ['song']
    }
</script>

<style scoped>

</style>
