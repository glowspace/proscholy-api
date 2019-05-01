<template>
    
    <table class="table">
        <tr v-for="song_lyric in song_lyrics" v-bind:key="song_lyric.id">
            <td>
                {{ song_lyric.name }}
            </td>
            <!-- <td style="width: 15px"><i class="fas fa-music"></i></td>
            <td>
                <a href="{{ $song_lyric->public_url }}">{{$song_lyric->getSearchTitle()}}</a>
            </td>

            <td style="width: 10px;"
                class="no-left-padding">
                @if($song_lyric->spotifyTracks()->count())
                    <i class="fab fa-spotify text-success"></i>
                @else
                    <i class="fab fa-spotify text-very-muted"></i>
                @endif
            </td>
            <td style="width: 10px;"
                class="no-left-padding">
                @if($song_lyric->soundcloudTracks()->count())
                    <i class="fab fa-soundcloud"
                        style="color: orangered;"></i>
                @else
                    <i class="fab fa-soundcloud text-very-muted"></i>
                @endif
            </td>
            <td style="width: 10px;"
                class="no-left-padding">
                @if($song_lyric->scoreExternals()->count() || $song_lyric->scoreFiles()->count())
                    <i class="fa fa-file-pdf"
                        style="color: #3961ad"></i>
                @else
                    <i class="fa fa-file-pdf text-very-muted"
                        style="color: #3961ad"></i>
                @endif
            </td>
            <td style="width: 10px;"
                class="no-left-padding">
                @if($song_lyric->youtubeVideos()->count())
                    <i class="fab fa-youtube text-danger"></i>
                @else
                    <i class="fab fa-youtube text-very-muted"></i>
                @endif
            </td> -->
        </tr>
        <!-- <tr>
            <td>
                <i>Žádná píseň s tímto názvem nebyla nalezena.</i>
            </td>
        </tr> -->
    </table>
</template>

<script>
    import { store } from "./store.js";
    import gql from 'graphql-tag';

    const fetch_items = gql`
        query FetchSongLyrics {
            song_lyrics {
                id,
                name
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

        methods: {

        }
    }
</script>
