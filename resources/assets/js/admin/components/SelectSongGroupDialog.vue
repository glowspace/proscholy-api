<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="600px">
      <template v-slot:activator="{ on }">
        <v-btn color="primary" dark v-on="on">Přidat do skupiny</v-btn>
      </template>
      <v-card>
        <v-card-title class="headline">Výběr skupiny písní
        </v-card-title>
        <v-card-text>
          <v-combobox
            v-model="song"
            :items="songs"
            item-value="id"
            :item-text="getSongLyricNames"
            label="Vyberte skupinu písní"
          ></v-combobox>

        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="onCancel">Zrušit</v-btn>
          <v-btn color="green darken-1" :disabled="song == undefined || song.id == undefined" flat @click="onSubmit">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";

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
    }
  }
};
</script>