<template>
  <div class="row">
    <div class="col-lg-9">
      <div class="card card-lyrics" id="cardLyrics">
        <div class="card-header p-1 song-links">
          <a class="btn btn-secondary" v-if="displayMode == 1" @click="displayMode = 0">
            <i class="fas fa-arrow-left"></i>
            <span>Zpět na text</span>
          </a>
          <a class="btn btn-secondary" v-if="displayMode == 0" @click="displayMode = 1">
            <i class="fas fa-file-alt"></i>
            <span class="d-none d-sm-inline">Noty</span>
          </a>
          <a class="btn btn-secondary" v-if="displayMode == 0" :class="{'chosen': translationsDisplay}"
          @click="translationsDisplay=!translationsDisplay">
            <i class="fas fa-language"></i>
            <span class="d-none d-sm-inline">Překlady</span>
          </a>
          <a class="btn btn-secondary" v-if="displayMode == 0">
            <i class="fas fa-file-pdf"></i>
            <span class="d-none d-sm-inline">Export</span>
          </a>
          <a class="btn btn-secondary float-right">
            <i class="fas fa-exclamation-triangle p-0"></i>
          </a>
          <!-- translations -->
          <div v-show="translationsDisplay && displayMode == 0">
            <div class="overflow-auto toolbox toolbox-u">
              <a
                class="btn btn-secondary float-right fixed-top position-sticky"
                v-on:click="translationsDisplay=false"
              >
                <i class="fas fa-times pr-0"></i>
              </a>
              překlady goes here
            </div>
          </div>
        </div>
        <div class="card-body py-2">
          <div class="d-flex justify-content-between">
            <template v-if="displayMode === 0">
              <div id="song-lyrics" style="overflow: hidden">
                <!-- here goes the song lyrics (vue components generated as a string by Laravel) -->
                <slot></slot>
              </div>
              <right-controls></right-controls>

              <!-- todo: preparing for two-column view -->
              <!-- <div id="song-lyrics" class="song-lyrics-divided">
                                <div class="row">
                                    <div class="col-sm-6 song-lyrics-refrains">
                                        <slot></slot>
                                    </div>
                                    <div class="col-sm-6 song-lyrics-verses">
                                        <slot></slot>
                                    </div>
                                </div>
              </div>-->
            </template>

            <template v-if="displayMode === 1">noty goes here</template>
          </div>
        </div>
        <div
          class="controls fixed-bottom position-sticky p-1"
          v-bind:class="{'card-footer': controlsDisplay}"
        >
          <div v-show="toolsDisplay && controlsDisplay">
            <div class="overflow-auto toolbox">
              <a class="btn btn-secondary float-right" v-on:click="toolsDisplay=false">
                <i class="fas fa-times pr-0"></i>
              </a>
              <div class="toolbox-item" v-if="chordMode != 0">
                <transposition v-model="transposition"></transposition>
              </div>

              <div class="toolbox-item" v-if="chordMode != 0">
                <chord-sharp-flat v-model="useFlatScale"></chord-sharp-flat>
              </div>

              <div class="toolbox-item" v-if="nChordModes != 1">
                <chord-mode v-model="chordMode" :n-chord-modes="nChordModes"></chord-mode>
              </div>

              <div class="toolbox-item">
                <font-sizer v-model="fontSizePercent"></font-sizer>
              </div>
            </div>
          </div>
          <!-- media -->
          <div v-show="mediaDisplay && controlsDisplay">
            <div class="overflow-auto media-card toolbox">
              <a
                class="btn btn-secondary float-right fixed-top position-sticky"
                v-on:click="mediaDisplay=false"
              >
                <i class="fas fa-times pr-0"></i>
              </a>
              <div class="row pt-2" v-if="song_lyric
              && (song_lyric.externals || song_lyric.files)
              && (song_lyric.externals.length || song_lyric.files.length) && !$apollo.loading">
                <div class="col-md-6" v-for="external in song_lyric.externals" v-bind:key="external.id" v-if="[1, 2, 3, 7].includes(external.type)">
                  <external-view :url="external.url" :media-id="external.media_id" :type="external.type" :authors="external.authors"></external-view>
                </div>
                <div class="col-md-6" v-for="file in song_lyric.files" v-bind:key="file.id" v-if="[1, 2, 3, 7].includes(fileTypeConvert(file.type))">
                  <external-view :url="file.url" :media-id="file.media_id" :type="fileTypeConvert(file.type)" :authors="file.authors"></external-view>
                </div>
              </div>
              <div v-else>
                <span v-if="$apollo.loading">
                  <i>Načítám...</i>
                </span>
                <span v-else>
                  <i>Žádná nahrávka nebyla nalezena.</i>
                </span>
              </div>
            </div>
          </div>
          <!-- control buttons -->
          <span v-show="controlsDisplay">
            <a
              class="btn btn-secondary"
              v-bind:class="{ 'chosen': toolsDisplay }"
              v-on:click="toolsDisplay=!toolsDisplay; mediaDisplay=false"
            >
              <i class="fas fa-sliders-h"></i>
              <span class="d-none d-sm-inline">Nástroje</span>
            </a>
            <a
              class="btn btn-secondary"
              v-if="renderMedia"
              v-bind:class="{ 'chosen': mediaDisplay }"
              v-on:click="mediaDisplay=!mediaDisplay; toolsDisplay=false"
            >
              <i class="fas fa-music"></i>
              <span class="d-none d-sm-inline">Nahrávky</span>
            </a>
            <div
              class="d-inline-block btn-group m-0"
              role="group"
              v-bind:class="{ 'chosen': autoscroll }"
            >
              <a class="btn btn-secondary" v-on:click="autoscroll=!autoscroll">
                <i
                  class="fas"
                  v-bind:class="[autoscroll?'pr-0 fa-stop-circle':'fa-arrow-circle-down']"
                ></i>
                <span class="d-none d-sm-inline" v-if="!autoscroll">Rolovat</span>
              </a><a
              class="btn btn-secondary" v-if="autoscroll">-</a><a
              class="btn btn-secondary" v-if="autoscroll">+</a>
            </div>
          </span>
          <a class="btn btn-secondary float-right" v-on:click="controlsToggle">
            <i
              class="fas pr-0"
              v-bind:class="[controlsDisplay?'fa-chevron-right':'fa-chevron-left']"
            ></i>
          </a>
        </div>
        <div class="card-footer">
          <!-- todo: asset img -->
          Zpěvník ProScholy.cz
          <img src="/img/logo_v2.png" width="20px">
          {{ new Date().getFullYear() }}
        </div>
      </div>
    </div>
    <!-- <div class="{{ $reversed_columns ? "col-lg-7" : "col-lg-3" }}">
                @if($song_l->scoreFiles()->count() > 0)
                    {{-- @component('client.components.thumbnail_preview', ['instance' => $song_l->scoreFiles()->first()])@endcomponent --}}
                    @component('client.components.media_widget', ['source' => $song_l->scoreFiles()->first()])@endcomponent
                @elseif ($song_l->scoreExternals()->count() > 0)
                    @component('client.components.media_widget', ['source' => $song_l->scoreExternals()->first()])@endcomponent
                @endif

                @if($song_l->youtubeVideos()->count() > 0 || $song_l->spotifyTracks()->count() > 0 || $song_l->soundcloudTracks()->count() > 0 || $song_l->audioFiles()->count() > 0)
                    <media-opener>
                    @if($song_l->spotifyTracks()->count() > 0)
                    <div class="media-opener"><i class="fab fa-spotify text-success"></i> Spotify</div>
                    @endif

                    @if($song_l->soundcloudTracks()->count() > 0)
                    <div class="media-opener"><i class="fab fa-soundcloud" style="color: orangered;"></i> SoundCloud</div>
                    @endif

                    @if($song_l->audioFiles()->count() > 0)
                    <div class="media-opener"><i class="fas fa-music"></i> MP3</div>
                    @endif

                    @if($song_l->youtubeVideos()->count() > 0)
                    <div class="media-opener"><i class="fab fa-youtube text-danger"></i> YouTube</div>
                    @endif
                    </media-opener>
                @endif
    </div>-->
  </div>
</template>

<style lang="scss">
.toolbox {
  padding: 0.25rem !important;
  margin-bottom: 0.25rem !important;

  background: white;
  .dark & {
    background: black;
  }

  &.toolbox-u {
    margin-top: 0.25rem !important;
    margin-bottom: 0 !important;
  }

  .toolbox-item {
    text-align: center;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    padding-bottom: 0.25rem;
    padding-top: 0.25rem;
    margin: 0.25rem;
    display: inline-block;
    border-radius: 0.125rem;
    
    border: 1px solid #dee2e6;
    .dark & {
      border-color: #211d19;
    }
  }
}
</style>


<script>
import { store } from "./store.js";

import FontSizer from "./FontSizer";
import ChordMode from "./ChordMode";
import ChordSharpFlat from "./ChordSharpFlat";
// import MediaOpener from './MediaOpener';
import RightControls from "./RightControls";
import Transposition from "./Transposition";
import ExternalView from "Public/components/ExternalView.vue";

// base_url = document.querySelector('#baseUrl').getAttribute('value');

import gql, { disableFragmentWarnings } from "graphql-tag";

const FETCH_SONG_LYRIC = gql`
  query($id: ID!) {
    song_lyric(id: $id) {
      id
      externals (orderBy: { field: "type", order: ASC }) {
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
        type
        authors {
          id
          name
          public_url
        }
      }
    }
  }
`;

export default {
  props: ["song-id", "render-media"],

  components: {
    FontSizer,
    ChordMode,
    ChordSharpFlat,
    ExternalView,
    RightControls,
    Transposition
  },

  data() {
    // use this only in SongView and Chord component
    // use v-model to bind data from every other component
    return store;
  },

  apollo: {
    song_lyric: {
      query: FETCH_SONG_LYRIC,
      variables() {
        return {
          id: this.songId
        };
      }
    }
  },

  methods: {
    controlsToggle: function() {
      this.controlsDisplay = !this.controlsDisplay;
      document.querySelector(".navbar.fixed-top").classList.toggle("d-none");
      console.log(this.song_lyric);
    },

    fileTypeConvert: function(type) {
      switch (type) {
        case 1:
          return 8;
          break;

        case 2:
          return 9;
          break;

        case 3:
          return 4;
          break;

        case 4:
          return 7;
          break;
    
        default:
          return type;
          break;
      }
    }
  }
};
</script>

