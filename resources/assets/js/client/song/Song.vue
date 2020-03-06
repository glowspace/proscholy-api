<template>
    <song-loading v-if="!ready"></song-loading>
    <song-detail v-else
                 :song="song"></song-detail>
</template>

<script>
    import SongAuthorLabel from "./components/SongAuthorLabel";
    // import Tags from "./components/Tags";
    import SongView from "./components/SongView";
    import SongDetail from "./SongDetail";
    import SongLoading from "./SongLoading";

    export default {
        name: "Song",
        components: {SongLoading, SongDetail, SongView, SongAuthorLabel},

        data: () => {
            return {
                ready: false,
                song: {}
            }
        },

        mounted() {

            setInterval(() => {
                this.mockSong();
                console.log('Loading mock song');
            }, 2000);
        },

        methods: {
            mockSong() {
                this.song = {
                    id: 1,
                    name: 'Nový song',
                    lang: '',
                    lang_string:'',
                    scoreExternals: [],
                scoreFiles: [],
                youtubeVideos: [],
                spotifyTracks: [],
                soundcloudTracks: [],
                audioFiles: [],
                authors: [],
                tags{id},
                has_chords,
                    has_lyrics,
                    songbook_records{number, songbook{id, name, shortcut}}
                };

                this.ready = true;
            }
        }
    }
</script>

<style scoped>

</style>
