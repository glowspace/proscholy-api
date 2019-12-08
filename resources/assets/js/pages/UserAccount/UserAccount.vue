<template>
    <div class="container">
        <h1>Uživatel {{ user ? user.username : '' }}</h1>
        <!-- <h2>Zpěvníky:</h2>

        <ul class="list-group" v-if="user">
            <li v-for="songbook in user_songbooks" v-bind:key="songbook.id" class="list-group-item d-flex justify-content-between align-items-center">
                {{ songbook.name }}
                <span class="badge badge-primary badge-pill">{{ songbook.songs.length }}</span>
                <ul>
                    <li v-for="song_obj in songbook.songs" v-bind:key="song_obj.song_id">
                        {{ getSongLyric(song_obj.song_id).name }}
                    </li>
                </ul>
            </li>
        </ul>
 
        <a @click="addNewSongbook" :disabled="new_songbook_name == ''">Přidat nový zpěvník</a>
        <input type="text" v-model="new_songbook_name"/> -->

        <a @click="signin">Přihlásit se přes Google</a>
    </div> 
</template>

<script>
import { GoogleProvider, auth } from 'Public/helpers/firebase_auth'

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
            search_string: "",
            new_songbook_name: "",
            user_ref: null,
            user: null, 
        }
    },

    apollo: {
        song_lyrics: {
            query: fetch_items,
            variables() {
                return {
                    search_str: this.search_string
                }
            },
        }
    },

    methods: {
        // getSongLyric(id) {
        //     if (!this.song_lyrics) {
        //         return;
        //     }

        //     return this.song_lyrics.filter(sl => sl.id == id)[0];
        // },

        async signin() {
            this.provider = GoogleProvider;
            let creds = await auth.signInWithPopup(this.provider);

            console.log({ creds });
            let token = await creds.user.getIdToken();

            console.log({ token });

            let headers = { Authorization: 'Bearer ' + token };
            let me = await axios.get('/firebase-auth/me', { headers });

            console.log({ me });

 
            // this.provider = GoogleProvider;
            // auth
            //     .signInWithPopup(this.provider)
            //     .then(result => {
            //         console.log(result);

            //         let uid = result.user.uid;

            //         let token = await result.user.getIdToken()
            //         console.log({ token })
            //         let headers = { Authorization: 'Bearer ' + token }
            //         let me = await axios.get('/api/me', { headers })

            //     })
            //     .catch(e => {
            //         this.$snotify.error(e.message);
            //         console.log(e);
            //     });
        }
    }
}
</script>