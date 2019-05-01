<template>

    <table class="table">
        <tr v-for="song_lyric in song_lyrics"
            v-bind:key="song_lyric.id">
            <td style="width: 15px"><i class="fas fa-music"></i></td>

            <td>
                <a :href="song_lyric.public_url">{{ song_lyric.name }}</a> -
                <span v-for="(author, index) in song_lyric.authors">
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
                   title="Tato píseň má nahrávku na Soundcloud.com"></i>
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

        <tr :v-if="song_lyrics">
            <td>
                <i>Žádná píseň s tímto názvem nebyla nalezena.</i>
            </td>
        </tr>
    </table>
</template>

<script>
    import {store} from "./store.js";
    import gql from 'graphql-tag';

    const fetch_items = gql`
        query FetchSongLyrics {
            song_lyrics {
                id,
                name,
                public_url,
                scoreExternals{id},
                scoreFiles{id},
                youtubeVideos{id},
                spotifyTracks{id},
                soundcloudTracks{id},
                authors{id, name}
            }
        }`;

    export default {
        props: [],

        data() {
            return {
                store: store,
                // custom data here
                // abc: ""
            }
        },

        apollo: {
            song_lyrics: {
                query: fetch_items,
                variables() {
                    return {
                        // has_lyrics: this.hasLyrics,
                        // has_authors: this.hasAuthors,
                        // has_chords: this.hasChords,
                        // has_tags: this.hasTags
                    }
                }
            }
        },

        methods: {}
    }
</script>
