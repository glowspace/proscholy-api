<template>
  <table class="table m-0">
    <template v-if="song_lyrics_results && song_lyrics_results.length && !$apollo.loading">
      <tr v-for="(song_lyric, index) in song_lyrics_results" v-bind:key="song_lyric.id">
        <td :class="[{'border-top-0': !index}, 'p-1 align-middle text-right']">
          <a
            class="p-2 pl-3 w-100 d-inline-block text-secondary"
            :href="song_lyric.public_url"
          >
            <!-- {{ (song_lyric.songbook_records[0])?(song_lyric.songbook_records[0].songbook.shortcut + song_lyric.songbook_records[0].number):"" }} -->
            {{ getSongNumber(song_lyric) }}
          </a>
        </td>
        <td :class="[{'border-top-0': !index}, 'p-1 align-middle']">
          <a class="p-2 w-100 d-inline-block" :href="song_lyric.public_url">{{ song_lyric.name }}</a>
        </td>
        <td :class="[{'border-top-0': !index}, 'p-1 align-middle']">
          <span v-for="(author, authorIndex) in song_lyric.authors" :key="authorIndex">
            <span v-if="authorIndex">,</span>
            <a :href="author.public_url" class="text-secondary">{{ author.name }}</a>
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
          <i v-else class="fas fa-align-left text-very-muted"></i>
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
          <i v-else class="fas fa-guitar text-very-muted"></i>
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
          <i v-else class="fa fa-file-alt text-very-muted"></i>
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
          <i v-else class="fas fa-headphones text-very-muted"></i>
        </td>
      </tr>
      <scroll-trigger @triggerIntersected="loadMore"/>
    </template>
    <tr v-else>
      <td v-if="!results_loaded" class="border-top-0 p-1">
        <i class="px-3 py-2 d-inline-block">Načítám...</i>
        <a class="btn btn-secondary float-right m-0" target="_blank"
        :href="'https://docs.google.com/forms/d/e/1FAIpQLScmdiN_8S_e8oEY_jfEN4yJnLq8idxUR5AJpFmtrrnvd1NWRw/viewform?usp=pp_url&entry.1025781741=' + currentUrl()">
          Nahlásit
        </a>
      </td>
      <td v-else class="border-top-0 p-1">
        <i class="px-3 py-2 d-inline-block">Žádná píseň odpovídající zadaným kritériím nebyla nalezena.</i>
        <a class="btn btn-secondary float-right m-0" target="_blank"
        :href="'https://docs.google.com/forms/d/e/1FAIpQLScmdiN_8S_e8oEY_jfEN4yJnLq8idxUR5AJpFmtrrnvd1NWRw/viewform?usp=pp_url&entry.1025781741=' + currentUrl()">
          Nahlásit
        </a>
      </td>
    </tr>
  </table>
</template>

<script>
    import gql from 'graphql-tag';
    import ScrollTrigger from './ScrollTrigger';

    // Query
    const fetch_items = gql`
        # warning this query is being cached on server-side, see App\Http\Middleware\CachedGraphql
        query FetchSongLyrics_cached($search_str: String) {
            song_lyrics: search_song_lyrics(search_string: $search_str) {
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
                has_lyrics,
                songbook_records{number, songbook{id, name, shortcut}}
            }
        }`;

    export default {
        props: ['search-string', 'selected-tags-dcnf', 'selected-songbooks', 'selected-tags', 'selected-languages', 'init'],

        components: { ScrollTrigger },

        data() {
            return {
                results_limit: 20,
                results_loaded: false,
                preferred_songbook_id: null
            }
        },

        computed: {
            /**
             * Filtered lyrics.
             */
            song_lyrics_results() {
                if (!this.song_lyrics) {
                    return [];
                }

                let res = this.song_lyrics;

                let categories = Object.values(this.selectedTagsDcnf);

                for (let category_tags of categories) {
                  let arr = Object.values(category_tags);

                  if (arr.length > 0) {
                      res = res.filter(song_lyric => {
                        for (var tag of song_lyric.tags) { 
                          if (arr.includes(tag.id)) {
                            return true;
                          }
                        }
                      });
                  }
                }

                // apply the songbooks filter
                if (Object.keys(this.selectedSongbooks).length > 0) {
                    res =  res.filter(song_lyric => { 
                        for (var record of song_lyric.songbook_records) {
                            if (this.selectedSongbooks[record.songbook.id]) {
                                return true;
                            }
                        }
                    });
                }

                // apply the languages filter
                if (Object.keys(this.selectedLanguages).length > 0) {
                    res =  res.filter(song_lyric => { 
                        if (this.selectedLanguages[song_lyric.lang]) {
                            return true;
                        }
                    });
                }
                
                res = res.slice(0, this.results_limit);

                return res;
            }

        },

        methods: {
            loadMore() {
                if (this.results_limit < this.song_lyrics.length)
                    this.results_limit += 20;
            },

            getSongNumber(song_lyric) {
              if (this.preferred_songbook_id === null) {
                return song_lyric.id;
              }

              let rec = song_lyric.songbook_records.filter(record => record.songbook.id === this.preferred_songbook_id)[0];

              return rec.songbook.shortcut + rec.number;
            },

            currentUrl() {
              return encodeURIComponent(window.location.href);
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
                debounce: 200,
                async result() {
                  this.$emit("query-loaded", null);
                  this.results_loaded = true;

                  // console.log(window.cachePersistor);
                  // console.log(await window.cachePersistor.getSize());
                },
            }
        },

        watch: {
          searchString() {
            this.results_loaded = false;
          },

          // init() {
          //   this.$apollo.queries.song_lyrics.skip = false;
          // }

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
