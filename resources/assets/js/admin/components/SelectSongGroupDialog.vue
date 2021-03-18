<template>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }">
            <v-btn :outline="outline" color="primary" dark v-on="on"
                >Přidat k písni nebo skupině písní</v-btn
            >
        </template>
        <v-card style="margin: 0">
            <v-card-title class="headline">Výběr </v-card-title>
            <v-card-text>
                <v-combobox
                    v-model="selected_song"
                    :items="songs_augmented"
                    item-value="id"
                    item-text="name_display"
                    :filter="filter"
                    label="Vyberte píseň resp. skupinu písní"
                ></v-combobox>

                <p v-if="$apollo.loading">
                    Chvilku strpení, načítám seznam skupin písní...
                </p>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat @click="onCancel"
                    >Zrušit</v-btn
                >
                <v-btn
                    color="green darken-1"
                    :disabled="
                        selected_song == undefined ||
                            selected_song.id == undefined
                    "
                    flat
                    @click="onSubmit"
                    >OK</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import gql from 'graphql-tag';
import {
    getSongLyricFullName,
    stringToSearchable
} from '../helpers/search_indexing';

const FETCH_SONGS = gql`
    query {
        songs {
            id
            song_lyrics {
                id
                name
                secondary_name_1
                secondary_name_2
                type
            }
        }
    }
`;

export default {
    props: ['outline'],

    data() {
        return {
            dialog: false,
            selected_song: undefined,
            songs_augmented: []
        };
    },

    apollo: {
        songs: {
            query: FETCH_SONGS,
            result() {
                this.songs_augmented = this.songs.map(song => ({
                    ...song,
                    name_search: stringToSearchable(
                        song.song_lyrics.map(getSongLyricFullName).join(', ')
                    ),
                    name_display: song.song_lyrics
                        .map(getSongLyricFullName)
                        .join(', ')
                }));
            }
        }
    },

    methods: {
        onCancel() {
            this.dialog = false;
        },

        onSubmit() {
            this.dialog = false;
            this.$emit('submit', this.selected_song);
        },

        filter(item, queryText) {
            if (item.header) return false;

            return item.name_search.indexOf(stringToSearchable(queryText)) > -1;
        }
    }
};
</script>
