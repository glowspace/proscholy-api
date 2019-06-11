<template>
    <div class="row">
        <div class="col-lg-9">
            <div class="card card-lyrics" id="cardLyrics">
                <div class="card-header p-1 song-links">
                    <a class="btn btn-secondary"><i class="fas fa-file-alt"></i> <span class="d-none d-sm-inline">Noty</span></a>
                    <a class="btn btn-secondary"><i class="fas fa-language"></i> <span class="d-none d-sm-inline">Překlady</span></a>
                    <a class="btn btn-secondary"><i class="fas fa-file-pdf"></i> <span class="d-none d-sm-inline">Export</span></a>
                    <a class="btn btn-secondary float-right"><i class="fas fa-exclamation-triangle"></i></a>
                </div>
                <div class="card-body py-2">
                    <div class="d-flex flex-row-reverse justify-content-between">
                        <right-controls></right-controls>
                        <div id="song-lyrics" style="overflow: hidden">
                            <slot></slot>
                        </div>
                    </div>
                </div>
                <div class="controls fixed-bottom position-sticky p-1" v-bind:class="{'card-footer': controlsDisplay}">
                    <div v-show="toolsDisplay && controlsDisplay">
                        <div class="card mb-1 p-1 overflow-auto">
                            <a class="btn btn-secondary float-right" v-on:click="toolsDisplay=false"><i class="fas fa-times pr-0"></i></a>
                            <transposition v-model="transposition"></transposition>
                            <chord-mode v-model="chordMode" :nChordModes="nChordModes"></chord-mode>
                            <chord-sharp-flat v-model="useFlatScale"></chord-sharp-flat>
                            <font-sizer v-model="fontSizePercent"></font-sizer>
                        </div>
                    </div>
                    <!-- media -->
                    <div v-show="mediaDisplay && controlsDisplay">
                        <div class="card mb-1 overflow-auto media-card">
                            <a class="btn btn-secondary float-right fixed-top position-sticky" v-on:click="mediaDisplay=false"><i class="fas fa-times pr-0"></i></a>
                            <slot name="media"></slot>
                        </div>
                    </div>
                    <!-- control buttons -->
                    <span v-if="controlsDisplay">
                        <a class="btn btn-secondary" v-bind:class="{ 'chosen': toolsDisplay }"
                        v-on:click="toolsDisplay=!toolsDisplay; mediaDisplay=false">
                            <i class="fas fa-sliders-h"></i> <span class="d-none d-sm-inline">Nástroje</span>
                        </a>
                        <a class="btn btn-secondary" v-if="!!this.$slots['media']" v-bind:class="{ 'chosen': mediaDisplay }"
                        v-on:click="mediaDisplay=!mediaDisplay; toolsDisplay=false">
                            <i class="fas fa-music"></i> <span class="d-none d-sm-inline">Nahrávky</span>
                        </a>
                        <div class="d-inline-block btn-group m-0" role="group" v-bind:class="{ 'chosen': autoscroll }">
                            <a class="btn btn-secondary" v-on:click="autoscroll=!autoscroll">
                                <i class="fas" v-bind:class="[autoscroll?'pr-0 fa-stop-circle':'fa-arrow-circle-down']"></i> <span class="d-none d-sm-inline" v-if="!autoscroll">Rolovat</span>
                            </a><span v-if="autoscroll"><a class="btn btn-secondary font-weight-bold" v-on:click="resize(-10)">-</a><a class="btn btn-secondary font-weight-bold" v-on:click="resize(10)">+</a></span>
                        </div>
                    </span>
                    <a class="btn btn-secondary float-right" v-on:click="controlsToggle"><i class="fas pr-0" v-bind:class="[controlsDisplay?'fa-chevron-right':'fa-chevron-left']"></i></a>
                </div>
                <div class="card-footer">
                    <!-- todo: asset img -->
                    Zpěvník ProScholy.cz <img src="img/logo_v2.png" width="20px">---- rok ----
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
            </div> -->
    </div>
</template>

<script>

import { store } from "./store.js";

import FontSizer from './FontSizer';
import ChordMode from './ChordMode';
import ChordSharpFlat from './ChordSharpFlat';
// import MediaOpener from './MediaOpener';
import RightControls from './RightControls';
import Transposition from './Transposition';
import ExternalView from 'Public/components/ExternalView.vue';

import gql, { disableFragmentWarnings } from "graphql-tag";

const FETCH_SONG_LYRIC = gql`
  query($id: ID!) {
    song_lyric(id: $id) {
      id
      name
    }
  }
`;

export default {
    props: ["song-id"],

    components: {
        FontSizer, ChordMode, ChordSharpFlat, ExternalView, RightControls, Transposition
    },
    
    data() {
        return store;
    },

    apollo: {
        song_lyric: {
            query: FETCH_SONG_LYRIC,
            variables() {
                return {
                    id: this.songId
                }
            }
        }
    },

    methods:{
        controlsToggle: function() {
            this.controlsDisplay = !(this.controlsDisplay);
            document.querySelector(".navbar.fixed-top").classList.toggle("d-none");
        }
    }
}
</script>

