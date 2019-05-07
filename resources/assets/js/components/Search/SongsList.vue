<template>
    <table class="table">
        <template v-if="song_lyrics_results && song_lyrics_results.length && !$apollo.loading">
            <tr v-for="song_lyric in song_lyrics_results"
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
                <td style="width: 10px;"
                    class="no-left-padding">
                    <i v-if="song_lyric.has_chords"
                       class="fas fa-guitar text-primary"
                       title="Tato píseň má přidané akordy."></i>
                    <i v-else
                       class="fas fa-guitar text-very-muted"></i>
                </td>

            </tr>
        </template>
        <tr v-else>
            <td v-if="$apollo.loading">
                <i>Načítám...</i>
            </td>
            <td v-else>
                <i>Žádná píseň s tímto názvem nebyla nalezena.</i>
            </td>
        </tr>
    </table>
</template>

<script>
    import gql from 'graphql-tag';

    // Query
    const fetch_items = gql`
        query FetchSongLyrics($search_str: String) {
            song_lyrics(search_string: $search_str, order_abc: true) {
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
        props: ['search-string', 'selected-tags'],

        data() {
            return {}
        },

        computed: {
            /**
             * Filtered lyrics.
             */
            song_lyrics_results() {
                if (Object.keys(this.selectedTags).length === 0) {
                    return this.song_lyrics;
                }

                return this.song_lyrics.filter(song_lyric => {
                    for (var tag of song_lyric.tags) {
                        if (this.selectedTags[tag.id]) {
                            return true;
                        }
                    }
                })
            }

        },

        // GraphQL client
        apollo: {
            song_lyrics: {
                query: fetch_items,
                variables() {
                    return {
                        search_str: this.searchString
                    }
                },
                // debounce waits 200ms for query refetching
                debounce: 200
            }
        },

        methods: {}
    }
</script>
