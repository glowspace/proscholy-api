<template>
    <div class="songs-list">
        <table class="table m-0">
            <template v-if="song_lyrics && song_lyrics.length && results_loaded">
                <tr v-for="(song_lyric, index) in song_lyrics"
                    v-bind:key="song_lyric.id">
                    <td :class="[{'border-top-0': !index}, 'p-1 align-middle text-right w-min']">
                        <router-link
                            class="p-2 pl-3 w-100 d-flex justify-content-between text-secondary"
                            :to="'/pisen/' + song_lyric.id + '/slug'"
                        >
                            <span>{{ getSongNumber(song_lyric, true) }}</span>
                            <span>{{ getSongNumber(song_lyric, false) }}</span>
                        </router-link>
                    </td>
                    <td :class="[{'border-top-0': !index}, 'p-1 align-middle']">
                        <router-link class="p-2 w-100 d-inline-block"
                                     :to="'/pisen/' + song_lyric.id + '/slug'">{{ song_lyric.name }}
                        </router-link>
                    </td>
                    <td :class="[{'border-top-0': !index}, 'p-1 align-middle']">
            <span v-for="(author, authorIndex) in song_lyric.authors"
                  :key="authorIndex">
              <span v-if="authorIndex">,</span>
              <a :href="author.public_url"
                 class="text-secondary">{{ author.name }}</a>
            </span>
                    </td>
                    <td
                        class="no-left-padding text-right text-uppercase small align-middle pr-3"
                        :class="{'border-top-0': !index}"
                    >
            <span
                :class="[{'text-very-muted': !song_lyric.has_lyrics}, 'pr-sm-0 pr-1']"
                v-if="song_lyric.lang != 'cs'"
                :title="song_lyric.lang_string"
            >{{ song_lyric.lang.substring(0, 3) }}</span>
                    </td>
                    <td
                        style="width: 10px;"
                        class="no-left-padding align-middle d-none d-sm-table-cell"
                        :class="{'border-top-0': !index}"
                    >
                        <i
                            v-if="song_lyric.has_lyrics"
                            class="fas fa-align-left text-secondary"
                            title="U této písně je zaznamenán text."
                        ></i>
                        <i v-else
                           class="fas fa-align-left text-very-muted"></i>
                    </td>
                    <td
                        style="width: 10px;"
                        class="no-left-padding align-middle d-none d-sm-table-cell"
                        :class="{'border-top-0': !index}"
                    >
                        <i
                            v-if="song_lyric.has_chords"
                            class="fas fa-guitar text-primary"
                            title="Tato píseň má přidané akordy."
                        ></i>
                        <i v-else
                           class="fas fa-guitar text-very-muted"></i>
                    </td>
                    <td
                        style="width: 10px;"
                        class="no-left-padding align-middle d-none d-sm-table-cell"
                        :class="{'border-top-0': !index}"
                    >
                        <i
                            v-if="song_lyric.scoreFiles.length > 0"
                            class="fa fa-file-alt text-danger"
                            title="K této písni jsou k dispozici noty."
                        ></i>
                        <i v-else
                           class="fa fa-file-alt text-very-muted"></i>
                    </td>
                    <td
                        style="width: 10px;"
                        class="no-left-padding pr-4 align-middle d-none d-sm-table-cell"
                        :class="{'border-top-0': !index}"
                    >
                        <i
                            v-if="(song_lyric.spotifyTracks.length + song_lyric.soundcloudTracks.length + song_lyric.youtubeVideos.length + song_lyric.audioFiles.length)"
                            class="fas fa-headphones text-success"
                            title="U této písně je k dispozici nahrávka."
                        ></i>
                        <i v-else
                           class="fas fa-headphones text-very-muted"></i>
                    </td>
                </tr>
                <scroll-trigger @triggerIntersected="loadMore"
                                :enabled="enable_more"/>
            </template>
            <tr v-else>
                <td v-if="!results_loaded"
                    class="border-top-0 p-1">
                    <span class="px-3 py-2 d-inline-block">Načítám...</span>
                    <a class="btn btn-secondary float-right m-0"
                       target="_blank"
                       :href="'https://docs.google.com/forms/d/e/1FAIpQLScmdiN_8S_e8oEY_jfEN4yJnLq8idxUR5AJpFmtrrnvd1NWRw/viewform?usp=pp_url&entry.1025781741=' + currentUrl()">
                        Nahlásit
                    </a>
                </td>
                <td v-else
                    class="border-top-0 p-1">
                    <span class="px-3 py-2 d-inline-block">Žádná píseň odpovídající zadaným kritériím nebyla nalezena.</span>
                    <a class="btn btn-secondary float-right m-0"
                       target="_blank"
                       :href="'https://forms.gle/AYXXxkWtDHQQ13856'">
                        Přidat píseň
                    </a>
                </td>
            </tr>
        </table>
        <div class="text-center">
            <div class="btn btn-primary"
                 v-if="enable_more && results_loaded">
                <span class="spinner-border spinner-border-sm"
                      role="status"
                      aria-hidden="true"></span>
                Načítám další výsledky (celkem {{ song_lyrics_paginated.paginatorInfo.total }})
            </div>
        </div>
    </div>
</template>

<script>
    import gql from 'graphql-tag';
    import ScrollTrigger from './ScrollTrigger';

    // Query
    const fetch_items = gql`
        query ($search_params: String, $page: Int, $per_page: Int) {
            song_lyrics_paginated: search_song_lyrics(search_params: $search_params, page: $page, per_page: $per_page) {
                data {
                  id,
                  name,
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
                  has_lyrics,
                  songbook_records{number, songbook{id, name, shortcut}}
              },
              paginatorInfo {
                currentPage,
                lastPage,
                total,
                hasMorePages
              }
            }
        }`;

    export default {
        props: ['search-string', 'selected-tags-dcnf', 'selected-songbooks', 'selected-tags', 'selected-languages', 'init'],

        components: {ScrollTrigger},

        data() {
            return {
                page: 1,
                per_page: 20,
                enable_more: true,
                results_loaded: false,
                preferred_songbook_id: null
            }
        },

        computed: {
            searchParams() {
                // encode the elasticsearch attributes into an object and send as JSON text
                // for docs see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl.html

                // all the searchable fields are defined in App\SongLyrics: toSearchableArray() and $mapping attr

                // also, the Kibana tool can be used for debugging the elasticsearch requests
                // see docker-compose.yml

                let query = {
                    'bool': {
                        'must': [],
                        'filter': []
                    }
                };

                // beware that not all attribute types can be used for sorting, this is why 'name_keyword' has been added to index
                let sort = [];

                if (this.searchString) {
                    query.bool.must.push({
                        'multi_match': {
                            'query': this.searchString,

                            'fields': ['name^2', 'lyrics', 'authors', '_id^50', 'songbook_records.sonbgook_number']
                        }
                    });
                } else {
                    // no search keyword provided, so use the alphabetical sorting
                    sort.push('name_keyword');
                }

                for (let category_tags of Object.values(this.selectedTagsDcnf)) {
                    let tag_ids = Object.values(category_tags).map(v => parseInt(v));

                    if (tag_ids.length) {
                        query.bool.filter.push({'terms': {'tag_ids': tag_ids}})
                    }
                }

                if (Object.keys(this.selectedLanguages).length) {
                    query.bool.filter.push({'terms': {'lang': Object.keys(this.selectedLanguages)}})
                }

                if (Object.keys(this.selectedSongbooks).length) {
                    query.bool.filter.push({'terms': {'songbook_records.songbook_id': Object.keys(this.selectedSongbooks)}})
                }

                // encode to a JSON string to pass as an argument

                const query_str = JSON.stringify({
                    "sort": sort,
                    "query": query
                });

                // // const query_base64 = Buffer.from(query_str).toString("base64");

                return query_str;
            },

            song_lyrics() {
                return this.song_lyrics_paginated ? this.song_lyrics_paginated.data : [];
            }
        },

        methods: {
            loadMore() {
                this.page++;

                this.$apollo.queries.song_lyrics_paginated.fetchMore({
                    variables: {
                        page: this.page,
                        per_page: this.per_page
                    },
                    updateQuery: (previousResult, {fetchMoreResult}) => {
                        const newSongLyrics = fetchMoreResult.song_lyrics_paginated.data;
                        const paginatorInfo = fetchMoreResult.song_lyrics_paginated.paginatorInfo;

                        this.enable_more = paginatorInfo.hasMorePages;

                        return {
                            song_lyrics_paginated: {
                                __typename: previousResult.song_lyrics_paginated.__typename,
                                // Merging the songLyrics lists
                                data: [...previousResult.song_lyrics_paginated.data, ...newSongLyrics],
                                paginatorInfo,
                            },
                        }
                    }
                });
            },

            getSongNumber(song_lyric, getfirstPart) {
                if (this.preferred_songbook_id === null) {
                    if (getfirstPart) {
                        return "";
                    } else {
                        return song_lyric.id;
                    }
                } else {
                    let rec = song_lyric.songbook_records.filter(record => record.songbook.id === this.preferred_songbook_id)[0];
                    if (getfirstPart) {
                        return rec.songbook.shortcut + " ";
                    } else {
                        return rec.number;
                    }
                }
            },

            currentUrl() {
                return encodeURIComponent(window.location.href);
            }
        },

        // GraphQL client
        apollo: {
            song_lyrics_paginated: {
                query: fetch_items,
                variables() {
                    return {
                        search_params: this.searchParams,
                        page: 1,
                        per_page: this.per_page
                    }
                },
                // debounce waits 200ms for query refetching
                debounce: 200,
                result(result) {
                    this.$emit("query-loaded", null);
                    this.enable_more = result.data.song_lyrics_paginated.paginatorInfo.hasMorePages;
                    this.results_loaded = true;
                },
            }
        },

        watch: {
            searchParams() {
                this.page = 1;
                this.results_loaded = false;
            },

            selectedSongbooks(val) {
                let arr = Object.keys(val);
                if (arr.length == 1) {
                    this.preferred_songbook_id = arr[0];
                } else {
                    this.preferred_songbook_id = null;
                }
            }
        }
    }
</script>
