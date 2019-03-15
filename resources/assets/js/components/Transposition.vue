<template>
    <div class="transpose-control-wrapper" style="display: inline-block">
        <span v-if="chordMode !== 0">
            <span>Transpozice: </span>
            <a class="btn btn-secondary" v-on:click="transposition = 0">0</a>
            <a class="btn btn-secondary" v-on:click="transpose(1)">+1</a>
            <a class="btn btn-secondary" v-on:click="transpose(-1)">-1</a>
            <a class="btn btn-secondary" v-on:click="useFlatScale = !useFlatScale">{{ useFlatScale ? '♭' : '#' }}</a>
        </span>
        <span v-if="nChordModes > 1">
            <a class="btn btn-secondary" v-on:click="switchChordMode()">{{ chordModeString }}</a>
        </span>
    </div>
</template>

<script>
    import { store } from "./store.js";

    export default {
        data() {
            return store;
        },

        computed: {
            chordModeString() {
                return this.chordMode_text[(this.chordMode + 1) % this.nChordModes];
            }
        },

        methods:{
            transpose: function(val) {
                this.transposition = (this.transposition + val) % 12;
                if (this.transposition < 0) {
                    this.transposition = 12 + this.transposition;
                }
                console.log(val);
            },

            switchChordMode: function() {
                this.chordMode = (this.chordMode + 1) % this.nChordModes;
            }
        }
    }
</script>
