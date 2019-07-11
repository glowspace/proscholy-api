<template>
    <table class="table m-0">
        <template v-if="song_lyrics_results && song_lyrics_results.length && !$apollo.loading">
            <tr v-for="(song_lyric, index) in song_lyrics_results"
                v-bind:key="song_lyric.id">
                <td :class="{'border-top-0': !index}">
                    <a :href="song_lyric.public_url">{{ song_lyric.name }}</a>
                    <span v-if="song_lyric.authors.length > 0">–</span>
                    <span v-for="(author, authorIndex) in song_lyric.authors"><span v-if="authorIndex">,</span>
                        <a :href="author.public_url" class="text-secondary">{{ author.name }}</a>
                    </span>
                </td>
                <td class="no-left-padding text-right text-uppercase small align-middle" :class="{'border-top-0': !index}">
                    <span :class="{'text-very-muted': !song_lyric.lyrics}" v-if="song_lyric.lang != 'cs'" :title="song_lyric.lang_string">{{ song_lyric.lang }}</span>
                </td>
                <td style="width: 10px;"
                    class="no-left-padding" :class="{'border-top-0': !index}">
                    <i v-if="song_lyric.lyrics"
                       class="fas fa-align-left text-secondary"
                       title="U této písně je zaznamenán text."></i>
                    <i v-else
                       class="fas fa-align-left text-very-muted"></i>
                </td>
                <td style="width: 10px;"
                    class="no-left-padding" :class="{'border-top-0': !index}">
                    <i v-if="song_lyric.has_chords"
                       class="fas fa-guitar text-primary"
                       title="Tato píseň má přidané akordy."></i>
                    <i v-else
                       class="fas fa-guitar text-very-muted"></i>
                </td>
                <td style="width: 10px;"
                    class="no-left-padding" :class="{'border-top-0': !index}">
                    <i v-if="song_lyric.scoreFiles.length > 0"
                       class="fa fa-file-alt text-danger"
                       title="K této písni jsou k dispozici noty."></i>
                    <i v-else
                       class="fa fa-file-alt text-very-muted"></i>
                </td>
                <td style="width: 10px;"
                    class="no-left-padding" :class="{'border-top-0': !index}">
                    <i v-if="(song_lyric.spotifyTracks.length + song_lyric.soundcloudTracks.length + song_lyric.youtubeVideos.length + song_lyric.audioFiles.length)"
                       class="fas fa-music text-success"
                       title="U této písně je k dispozici nahrávka."></i>
                    <i v-else
                       class="fas fa-music text-very-muted"></i>
                </td>
            </tr>
        </template>
        <tr v-else>
            <td v-if="$apollo.loading" class="border-top-0">
                <i>Načítám...</i>
            </td>
            <td v-else class="border-top-0">
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
                lang,
                lang_string,
                scoreExternals: externals(type: 4){id},
                scoreFiles: files(type: 3){id},
                youtubeVideos: externals(type: 3){id},
                spotifyTracks: externals(type: 1){id},
                soundcloudTracks: externals(type: 2){id},
                audioFiles: files(type: 4){id},
                authors{id, name, public_url}
                tags{id},
                has_chords,
                lyrics
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
