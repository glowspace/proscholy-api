<template>
  <v-dialog v-model="dialog" persistent max-width="600px">
    <template v-slot:activator="{ on }">
      <v-btn :outline="outline" color="primary" dark v-on="on">Přidat k písni nebo skupině písní</v-btn>
    </template>
    <v-card>
      <v-card-title class="headline">Výběr
      </v-card-title>
      <v-card-text>
        <v-combobox
          v-model="song"
          :items="songs"
          item-value="id"
          :item-text="getSongLyricNames"
          :filter="filter"
          label="Vyberte píseň resp. skupinu písní"
        ></v-combobox>

      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="green darken-1" flat @click="onCancel">Zrušit</v-btn>
        <v-btn color="green darken-1" :disabled="song == undefined || song.id == undefined" flat @click="onSubmit">OK</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";
import removeDiacritics from '../helpers/removeDiacritics';

const FETCH_SONGS = gql`
  query {
    songs {
      id
      song_lyrics {
        id
        name
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
      song: undefined
    };
  },

  apollo: {
    songs: {
      query: FETCH_SONGS
    }
  },

  methods: {
    getSongLyricNames(song) {
      let name = "";
      for (let sl of song.song_lyrics) {
        name += sl.name + ", ";
      }

      return name.slice(0, name.length - 2);
      // return song.song_lyrics[0].name;
    },

    onCancel(){
      this.dialog = false;
    },

    onSubmit(){
      this.dialog = false;
      this.$emit("submit", this.song);
    },

    filter(item, queryText, itemText) {
      if (item.header) return false;

      const hasValue = val => (val != null ? val : "");

      const text = removeDiacritics(hasValue(itemText));
      const query = removeDiacritics(hasValue(queryText));

      return (
        text
          .toString()
          .toLowerCase()
          .indexOf(query.toString().toLowerCase()) > -1
      );
    },
  }
};
</script>