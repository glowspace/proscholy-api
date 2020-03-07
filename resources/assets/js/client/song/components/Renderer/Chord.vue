<template>
    <span class="chord" v-bind:style="{ fontSize: chordSharedStore.fontSizePercent + '%' }">
        <!-- the if condition syntax is weird but necessary here -->
        <span class="chord-sign" v-if="displayChordSign">
            <span v-if="isOptional">(</span>
            <span class="chord-base">{{baseNote}}</span>
            <span class="chord-variant">{{variant}}</span>
            <span class="chord-extension">{{extension}}</span>
            <span class="chord-bass" v-if="bass.length!==0">/{{bassNote}}</span>
            <span class="chord-right-bracket" v-if="isOptional">)</span>
        </span><span :class="['chord-text', isDivided == 0 ? 'chord-text-spaced' : '']">
            <slot></slot>
        </span><span class="chord-line" v-if="isDivided == 1"></span>
    </span>
</template>

<script>
    import { store } from "../../store.js";

    export default {
        props: ['base', 'variant', 'extension', 'bass', 'isDivided', 'isSubstitute', 'isOptional'],

        data() {
            return {
                chordSharedStore: store
            }
        },

        created() {
            // each chords notifies its state to the global store.js file

            // I'm a chord that has a chord sign -> allow to display chords + to switch to extended chord mode
            if (this.base != "" && this.chordSharedStore.nChordModes == 1) {
                this.chordSharedStore.nChordModes = 3;
                this.chordSharedStore.chordMode = 2;
            }

            // After being decided between #/b, do not use later chords
            // (there can be some transposition later in the song)
            if (this.chordSharedStore.useFlatScale_notified) {
                return;
            }

            // I'm a B-flat chord -> set flats as default
            if (this.base === "B" ||
                (this.base.length > 1 && this.base[1] === "b")) {
                this.chordSharedStore.useFlatScale = true;
                this.chordSharedStore.useFlatScale_notified = true;
            }
        },

        computed: {
            baseNote() {
                if (this.base == "") { return ""; }

                return this.transposeChordBy(this.base, this.chordSharedStore.transposition, this.chordSharedStore.useFlatScale);
            },

            bassNote() {
                if (this.bass == "") { return ""; }

                return this.transposeChordBy(this.bass, this.chordSharedStore.transposition, this.chordSharedStore.useFlatScale);
            },

            displayChordSign() {
                if (this.chordSharedStore.chordMode === 0) return false;
                if (this.chordSharedStore.chordMode === 1) return !(this.isSubstitute);
                if (this.chordSharedStore.chordMode === 2) return true;
            }
        },

        methods:{
            transposeChordBy(chord, semitones, useFlatScale) {
                // Chromatic scale starting from C using flats only.
                const FLAT_SCALE = ["C", "Db", "D", "Eb", "E", "F", "Gb", "G", "Ab", "A", "B", "H"];

                // Chromatic scale starting from C using sharps only.
                const SHARP_SCALE = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "H"];

                let scale = useFlatScale ? FLAT_SCALE : SHARP_SCALE;
                let chord_i = FLAT_SCALE.indexOf(chord);
                if (chord_i === -1) {
                    chord_i = SHARP_SCALE.indexOf(chord);
                }

                let new_i = (chord_i + semitones + 12) % 12;

                return scale[new_i];
            }
        }
    }
</script>

<style lang="scss">
    .chord{
        position: relative;
        display: inline-block;

        &-sign{
            display: flex;
            justify-content: flex-start;
            transition: 100ms;
            margin-bottom: -0.4em;
            color: #1d6dab;
        }

        &-base{
            font-weight: bold;
            margin-right: 0.4em;
        }

        &-variant{
            position: relative;
            left: -0.4em;
        }

        &-extension{
            font-size: 0.8em;
            position: relative;
            left: -0.4em;
        }

        &-bass {
            // font-weight: bold;
            color: #6b78af;
            margin-right: 0.4em;
            margin-left: -0.35em;
        }

        &-right-bracket {
            margin-left: -0.4em;
            margin-right: 0.4em;
        }

        &-text {
            display: inline-block;
            // this is so that the chord line is not displayed on the text
            position: relative;
            background: white;
            z-index: 2;
        }

        &-text-spaced{
            margin-right: 0.1em;
        }

        &-line {
            display: block;
            position: relative;
            width: calc(100% - 0.6em);
            height: 1.1px;
            background: #b9b9b9;
            top: -0.5em;
            right: -0.5em;
        }
    }

    .song-part-inline .chord-text {
        display: none;
    }
</style>
