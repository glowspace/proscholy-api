<template>
    <song-loading v-if="$apollo.loading"></song-loading>
    <song-detail v-else
                 :song="song"></song-detail>
</template>

<script>
    import SongAuthorLabel from "./components/SongAuthorLabel";
    // import Tags from "./components/Tags";
    import SongView from "./components/SongView";
    import SongDetail from "./SongDetail";
    import SongLoading from "./SongLoading";

    import gql, {disableFragmentWarnings} from "graphql-tag";

    const FETCH_SONG_LYRIC = gql`
  query($id: ID!) {
    song_lyric(id: $id) {
      id
      name
      externals(orderBy: { field: "type", order: ASC }) {
        id
        public_name
        url
        type
        media_id
        authors {
          id
          name
          public_url
        }
      }
      files {
        id
        public_name
        url
        download_url
        type
        authors {
          id
          name
          public_url
        }
      }
      song {
        song_lyrics {
          id
          name
          public_url
          type
          authors {
            id
            name
            public_url
          }
          lang
          lang_string
        }
      }
      capo
      # songbook_records{number, songbook{id, name, shortcut}}
    }
  }
`;

    import {clone} from 'lodash';

    export default {
        name: "Song",
        components: {SongLoading, SongDetail, SongView, SongAuthorLabel},

        data: () => {
            return {
                ready: false,
                songId: 1,
                song: {}
            }
        },

        mounted() {

        },

        apollo: {
            song: {
                query: FETCH_SONG_LYRIC,
                variables() {
                    return {
                        id: this.songId
                    };
                }
            }
        },

        methods: {
            mockSong() {
                this.song = {
                    id: 1,
                    name: 'Nový song',
                    lang: '',
                    lang_string: '',
                    scoreExternals: [],
                    scoreFiles: [],
                    youtubeVideos: [],
                    spotifyTracks: [],
                    soundcloudTracks: [],
                    audioFiles: [],
                    authors: [],
                    tags: [],
                    has_chords: false,
                    has_lyrics: true,
                    songbook_records: []
                };

                this.ready = true;
            }
        }
    }
</script>

<style scoped>

</style>
