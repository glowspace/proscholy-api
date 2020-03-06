<template>
    <div class="container">
        <h1>Uživatelský účet</h1>

        <div v-if="isLoggedIn">
            <p>Přihlášený uživatel: {{ user.name }}</p>
            <p>Email: {{ user.email }}</p>

            <h2>Zpěvníky:</h2>

            <ul class="list-group">
                <li v-for="songbook in user.songbooks"
                    v-bind:key="songbook.id"
                    class="list-group-item d-flex justify-content-between align-items-center">
                    {{ songbook.name }}
                    <span class="badge badge-primary badge-pill">{{ songbook.song_lyrics.length }}</span>
                    <ul>
                        <li v-for="song_lyric in songbook.song_lyrics"
                            v-bind:key="song_lyric.id">
                            {{ song_lyric.name }}
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- <a @click="addNewSongbook" :disabled="new_songbook_name == ''">Přidat nový zpěvník</a>
            <input type="text" v-model="new_songbook_name"/> -->
        </div>
        <div v-else>

            <a @click="signin">Přihlásit se přes Google</a>
        </div>
    </div>
</template>

<script>
    import {GoogleProvider, auth} from 'Public/helpers/firebase_auth'

    import gql from 'graphql-tag'

    const fetch_items = gql`
    query ($search_params: String, $page: Int, $per_page: Int) {
        song_lyrics_paginated: search_song_lyrics(search_params: $search_params, page: $page, per_page: $per_page) {
            data {
                id,
                name
            },
            paginatorInfo {
                currentPage,
                lastPage,
                total,
                hasMorePages
            }
        }
    }`;

    const fetch_user = gql`
     query {
            user: current_public_user {
                id,
                name,
                email,
                playlists {
                    id,
                    name,
                    song_lyrics {
                        id,
                        name
                    }
                }
            }
        }`;

    /**
     * Account component.
     *
     * TODO: frontend
     */
    export default {
        data() {
            return {
                user: null,
                token: null,
                // search_string: "",
                new_songbook_name: "",

                isLoggedIn: false
            }
        },

        name: 'login',

        apollo: {
            song_lyrics_paginated: {
                query: fetch_items,
                variables() {
                    return {
                        search_params: this.searchParams,
                        page: 1,
                        per_page: 100
                    }
                },
                debounce: 200,
                // result(result) {
                //     this.$emit("query-loaded", null);
                //     this.enable_more = result.data.song_lyrics_paginated.paginatorInfo.hasMorePages;
                //     this.results_loaded = true;
                // },
            }
        },

        mounted() {
            auth.onAuthStateChanged(this.performUserQuery);
        },

        methods: {
            async performUserQuery(firebaseuser) {
                if (firebaseuser) {
                    var token = await firebaseuser.getIdToken();

                    this.$apollo.query({
                        query: fetch_user,
                        manual: true,
                        context: {
                            'headers': {
                                'Authorization': 'Bearer ' + token
                            }
                        }
                    }).then((response) => {
                        console.log(response.data.user);
                        this.user = response.data.user;
                        this.isLoggedIn = true;

                    }).catch((exc) => {

                    });
                }
            },

            async signin() {
                this.provider = GoogleProvider;

                var user = auth.currentUser;

                let token = "";

                // if (user) {
                //     token = await user.getIdToken();
                //     console.log(token);
                // } else {
                let creds = await auth.signInWithPopup(this.provider);

                console.log({creds});
                token = await creds.user.getIdToken();
                // }

                console.log({token});
                // console.log({ me });

                // todo: refresh the page
            }
        },

        computed: {
            searchParams() {
                let query = {
                    'bool': {
                        'must': [],
                        'filter': []
                    }
                };

                return JSON.stringify({
                    "sort": ['name_keyword'],
                    "query": query
                });
            },

            song_lyrics() {
                return this.song_lyrics_paginated ? this.song_lyrics_paginated.data : [];
            }
        }
    }
</script>
