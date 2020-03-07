<template>
    <div>
        <div :class="getSongPartClass(part)" v-for="(part, key) in song_lyric_parts" v-bind:key="key">
            <div class="song-line" v-for="(line, key2) in part.songLines" v-bind:key="key2">
                <!-- todo: song part tag -->

                <chord v-for="(chord, key3) in line.chords" v-bind:key="key3"
                    :base="chord.base"
                    :variant="chord.variant"
                    :extension="chord.extension"
                    :bass="chord.bass"
                    :isDivided="chord.isDivided"
                    :isOptional="chord.isOptional"
                    :isSubstitute="chord.isSubstitute">
                    {{ chord.text }}
                </chord>
            </div>
        </div>
    </div>
</template>

<script>
import gql from "graphql-tag";
import Chord from "./Chord";

const FETCH_SONG_LYRIC_PARTS = gql`
    query($id: ID!) {
        song_lyric_parts(id: $id) {
        type
        isHidden
        isHiddenText
        isEmpty
        isVerse
        isRefrain
        isInline
        songLines {
            chords {
            base
            variant
            extension
            bass
            isSubstitute
            isOptional
            isDivided
            text
            }
        }
        }
    }
`;

export default {
    props: ["songId"],

    components: {
        Chord
    },

    apollo: {
        song_lyric_parts: {
            query: FETCH_SONG_LYRIC_PARTS,
            variables() {
                return {
                    id: this.songId
                };
            }
        }
    },

    methods: {
        getSongPartClass(part) {
            let cl = "song-part";

            if (part.isRefrain) cl += ' song-part-refrain';
            if (part.isHidden) cl += ' song-part-hidden';
            if (part.isHiddenText) cl += ' song-part-hidden-text';
            if (part.isInline) cl += ' song-part-inline';

            return cl;
        },
    }
};
</script>
