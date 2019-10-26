<template>
    <div class="container">
        <h1>Uživatel {{ user.username }}</h1>
        <h2>Zpěvníky:</h2>

        <ul class="list-group">
            <li v-for="songbook in user.songbooks" v-bind:key="songbook.id" class="list-group-item d-flex justify-content-between align-items-center">
                {{ songbook.name }}
                <span class="badge badge-primary badge-pill">{{ songbook.songs.length }}</span>
                <ul>
                    <li v-for="song_id in songbook.songs" v-bind:key="song_id">
                        {{ getSongLyric(song_id).name }}
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script>
import { db } from 'Public/helpers/firebasedb'
import gql from 'graphql-tag'

const fetch_items = gql`
    # warning this query is being cached on server-side, see App\Http\Middleware\CachedGraphql
     query FetchSongLyrics_cached($search_str: String) {
            song_lyrics: search_song_lyrics(search_string: $search_str) {
                id,
                name
            }
        }`;

export default {
    data() {
        return {
            users:[],
            search_string: ""
        }
    },

    firestore: {
        users: db.collection('users')
    },

    apollo: {
        song_lyrics: {
            query: fetch_items,
            variables() {
                return {
                    search_str: this.search_string
                }
            },
            // async result() {
            //     this.$emit("query-loaded", null);
            //     this.results_loaded = true;

            //     // console.log(window.cachePersistor);
            //     // console.log(await window.cachePersistor.getSize());
            // },
        }
    },

    computed: {
        user() {
            // todo return current authenticated user
            return this.users[0]
        }
    },

    methods: {
        getSongLyric(id) {
            if (!this.song_lyrics) {
                return;
            }

            return this.song_lyrics.filter(sl => sl.id == id)[0];
        }
    }
}
</script>