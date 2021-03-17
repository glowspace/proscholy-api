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
                    v-model="song"
                    :items="songs_flat"
                    item-value="song_id"
                    item-text="name_display"
                    :filter="filter"
                    label="Vyberte píseň resp. skupinu písní"
                ></v-combobox>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat @click="onCancel"
                    >Zrušit</v-btn
                >
                <v-btn
                    color="green darken-1"
                    :disabled="song == undefined || song.song_id == undefined"
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
import removeDiacritics from '../helpers/removeDiacritics';

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

const songLyricFullName = sl => {
    var name = sl.name;
    if (sl.secondary_name_1) {
        name += '(' + sl.secondary_name_1;
        if (sl.secondary_name_2) {
            name += ', ' + sl.secondary_name_2;
        }
        name += ')';
    }
    return name;
};

const stringToSearchable = str => removeDiacritics(str ?? '').toLowerCase();

export default {
    props: ['outline'],

    data() {
        return {
            dialog: false,
            song: undefined,
            songs_flat: []
        };
    },

    apollo: {
        songs: {
            query: FETCH_SONGS,
            result() {
                this.songs_flat = this.songs.map(song => ({
                    song_id: song.id,
                    name_search: stringToSearchable(
                        song.song_lyrics.map(songLyricFullName).join(', ')
                    ),
                    name_display: song.song_lyrics
                        .map(songLyricFullName)
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
            this.$emit('submit', this.song);
        },

        filter(item, queryText) {
            if (item.header) return false;

            return item.name_search.indexOf(stringToSearchable(queryText)) > -1;
        }
    }
};
</script>
