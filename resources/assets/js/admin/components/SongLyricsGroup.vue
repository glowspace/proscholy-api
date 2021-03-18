<template>
    <div>
        <button-color-toggle
            v-for="song_lyric in orderedValues"
            v-bind:key="song_lyric.id"
            :colors="editId == song_lyric.id ? colors : colors_def"
            :hover-texts="hoverTexts"
            v-model="song_lyric.type"
            v-on:input="updated(song_lyric)"
        >
            {{ getSongLyricFullName(song_lyric) }}
        </button-color-toggle>
    </div>
</template>

<script>
import ButtonColorToggle from '../components/ButtonColorToggle.vue';
import { getSongLyricFullName } from '../helpers/search_indexing';

export default {
    components: {
        ButtonColorToggle
    },

    props: ['value', 'edit-id'],

    data() {
        return {
            colors_def: [
                'info lighten-1',
                'success lighten-1',
                'warning lighten-1'
            ],
            colors: ['info', 'success', 'warning'],
            hoverTexts: ['Originál', 'Překlad', 'Autorizovaný překlad']
            // types - 0: original 1: translation 2: authorized translation
        };
    },

    computed: {
        orderedValues() {
            // .slice() is to make .sort immutable
            // see https://stackoverflow.com/questions/30431304/functional-non-destructive-array-sort
            return this.value.slice().sort((a, b) => {
                if (a.id == this.editId) return -1;
                if (b.id == this.editId) return 1;
                return a.name.localeCompare(b.name);
            });
        }
    },

    methods: {
        updated(last) {
            // check the consistency
            if (last.type === 0) {
                // allow only one original -> set other originals to translation
                for (var song_lyric of this.value) {
                    if (song_lyric.type == 0 && song_lyric.id !== last.id) {
                        Vue.set(song_lyric, 'type', 1);
                    }
                }
            }
        },
        getSongLyricFullName: getSongLyricFullName
    }
};
</script>
