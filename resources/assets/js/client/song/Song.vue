<template>
    <song-loading v-if="!ready"></song-loading>
    <song-detail v-else :song="song"></song-detail>
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
        }
    }
</script>

<style scoped>

</style>
