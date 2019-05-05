<template>
    <table class="table">
        <template v-if="song_lyrics && song_lyrics.length && !$apollo.loading">
            <tr v-for="song_lyric in song_lyrics"
                v-bind:key="song_lyric.id">
                <td style="width: 15px"><i class="fas fa-music"></i></td>

                <td>
                    <a :href="song_lyric.public_url">{{ song_lyric.name }}</a>
                    <span v-if="song_lyric.authors.length > 0">-</span>
                    <span v-for="(author, index) in song_lyric.authors"
                        v-bind:key="author.id">
                        {{ author.name }}<span v-if="index !== song_lyric.authors.length - 1">, </span>
                    </span>
                </td>

                <td style="width: 10px;"
                    class="no-left-padding">
                    <i v-if="song_lyric.spotifyTracks.length > 0"
                    class="fab fa-spotify text-success"
                    title="Tato píseň má nahrávku na Spotify."></i>
                    <i v-else
                    class="fab fa-spotify text-very-muted"></i>
                </td>
                <td style="width: 10px;"
                    class="no-left-padding">
                    <i v-if="song_lyric.soundcloudTracks.length > 0"
                    class="fab fa-soundcloud"
                    style="color: orangered;"
                    title="Tato píseň má nahrávku na Soundcloud."></i>
                    <i v-else
                    class="fab fa-soundcloud text-very-muted"></i>
                </td>
                <td style="width: 10px;"
                    class="no-left-padding">
                    <i v-if="song_lyric.scoreFiles.length > 0"
                    class="fa fa-file-pdf"
                    style="color: #3961ad"
                    title="K této písni jsou k dispozici noty."></i>
                    <i v-else
                    class="fa fa-file-pdf text-very-muted"></i>
                </td>
                <td style="width: 10px;"
                    class="no-left-padding">
                    <i v-if="song_lyric.youtubeVideos.length > 0"
                    class="fab fa-youtube text-danger"
                    title="Tato píseň má video na YouTube."></i>
                    <i v-else
                    class="fab fa-youtube text-very-muted"></i>
                </td>
            </tr>
        </template>
        <tr v-else>
            <td v-if="$apollo.loading">
                <i>Načítání</i>
            </td>
            <td v-else>
                <i>Žádná píseň s tímto názvem nebyla nalezena.</i>
            </td>
        </tr>
    </table>
</template>

<script>
    import {store} from "./store.js";
    import gql from 'graphql-tag';

    // Query
    const fetch_items = gql`
        query FetchSongLyrics($search_str: String) {
            song_lyrics(search_string: $search_str) {
                id,
                name,
                public_url,
                scoreExternals: externals(type: 4){id},
                scoreFiles: files(type: 3){id},
                youtubeVideos: externals(type: 3){id},
                spotifyTracks: externals(type: 1){id},
                soundcloudTracks: externals(type: 2){id},
                authors{id, name}
                tags{id}
            }
        }`;

    export default {

        data() {
            return {
                store: store,
                // custom data here
                song_lyrics: [],
            }
        },

        computed: {
            selected_tags() {
                if (this.store.tagsData === undefined) {
                    return [];
                }

                return this.store.tagsData.filter(tag => {
                    return tag.selected === true
                })
            },

            /**
             * Filtered lyrics.
             */
            song_lyrics_results() {
                if (this.store.tagsData.length === 0)
                    return this.song_lyrics;

                return this.song_lyrics.filter(song_lyric => {
                    return song_lyric.tags.includes(this.selected_tags)
                })
            }

        },

        // GraphQL client
        apollo: {
            song_lyrics: {
                query: fetch_items,
                variables() {
                    return {
                        // has_lyrics: this.hasLyrics,
                        // has_authors: this.hasAuthors,
                        // has_chords: this.hasChords,
                        search_str: this.store.search_string
                    }
                }
            }
        },

        methods: {}
    }
</script>
