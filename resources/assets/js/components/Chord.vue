<template>
    <span class="chord">
        <span class="chord-sign">
            <span class="chord-base">{{baseChord}}</span>
            <span class="chord-variant">{{variant}}</span>
            <span class="chord-bass" v-if="bass.length!==0">/{{bass}}</span>
        </span>
        <span class="chord-text"><slot></slot></span><span class="chord-dash" v-if="isDivided == 1">-</span>
    </span>
</template>

<style lang="scss">
    .chord{
        position: relative;
        transition: 100ms;
        display: inline-block;

        // padding-top: 1.2rem;

        &-sign{
            display: flex;
            justify-content: flex-start;
            transition: 100ms;
            margin-bottom: -0.3rem;
        }

        &-base{
            font-weight: bold;
            margin-right: 0.4rem;
        }

        &-variant{
            // font-size: 0.8em;
            // color: rgb(3, 30, 54);
            position: relative;
            left: -0.4em;
            // top: -0.2em;
        }

        &-bass{
            font-weight: bold;
            // color: #adc6db;
            margin-right: 0.4rem;
            margin-left: -0.2rem;
            transition: 100ms;
        }

        &-text{
            display: inline-block;
            // background: white;
            position: relative;
        }

        &-dash{
            position: absolute;
            bottom: 0;
            display: none;
        }

        &:hover{
            // background: #d0e6f9;
            padding: 0rem 0.2rem 0rem 0.2rem;

            .chord-text{
                // background: #d0e6f9;
            }

            .chord-bass{
                color: black;
            }
        }
    }
</style>

<script>
    import { store } from "./store.js";
    
    export default {
        props: ['base', 'variant', 'bass', 'text', 'isDivided'],

        data() {
            return store;
        },

        computed: {
            baseChord() {
                if (this.base == "") {
                    return "";
                }

                return this.transposeChordBy(this.base, this.transposition, this.useFlatScale);
            },

            bassChord() {
                if (this.bass == "") {
                    return "";
                }

                return this.transposeChordBy(this.bass, this.transposition, this.useFlatScale);
            }
        },

        methods:{
            transposeChordBy(chord, semitones, useFlatScale) {
                // Chromatic scale starting from C using flats only.
                const FLAT_SCALE = ["C", "Db", "D", "Eb", "E", "F", "Gb", "G", "Ab", "A", "B", "Cb"];

                // Chromatic scale starting from C using sharps only.
                const SHARP_SCALE = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "H"];

                let scale = useFlatScale ? FLAT_SCALE : SHARP_SCALE;
                let chord_i = FLAT_SCALE.indexOf(chord);
                if (chord_i === -1) {
                    chord_i = SHARP_SCALE.indexOf(chord);
                }

                let new_i = (chord_i + semitones) % 12;

                return scale[new_i];


                // scale = FLAT_SCALE;

                // for (let i = 0; i < N_KEYS; i++) {
                //     map[FLAT_SCALE[i]] = scale[(i + semitones + N_KEYS) % N_KEYS];
                //     map[SHARP_SCALE[i]] = scale[(i + semitones + N_KEYS) % N_KEYS];
                // }


            }
        }

        // created: function (){
        //     console.log(this.computed);
        // }
    }
</script>