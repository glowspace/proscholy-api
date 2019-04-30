<template>
<!-- v-app must wrap all the components -->
  <v-app>
    <v-container grid-list-xs>
      <v-layout row>
        <v-flex xs5 offset-xs7 md3 offset-md9>
          <!-- <input type="text" v-model="search_string" style="width: 100%"> -->
          <v-text-field v-model="search_string" label="Vyhledávání"></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout row>
        <v-flex xs12>
          <v-data-table
            :headers="headers"
            :items="song_lyrics"
            :search="search_string"
            :filter="formFilter"
            class="users-list">
            
            <template v-slot:items="props">
              <td>
                <a :href="'/admin/song/' + props.item.id">{{ props.item.name }}</a>
              </td>
              <td>
                <span v-if="props.item.is_original">Originál</span>
                <span v-if="!props.item.is_original">Překlad</span>
              </td>
              <td>{{ props.item.updated_at }}</td>
              <td>
                <span v-if="props.item.is_published">Ano</span>
                <span v-if="!props.item.is_published">Ne</span>
              </td>
              <td>
                <span v-if="props.item.is_approved_by_author">Ano</span>
                <span v-if="!props.item.is_approved_by_author">Ne</span>
              </td>
              <td>
                <a href="#" v-on:click="askForm(props.item.id)">Smazat</a>
              </td>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<style scope>
  input {
    border: none;
  }
</style>

<script>

import gql from 'graphql-tag';

import removeDiacritics from '../helpers/removeDiacritics';

const fetch_song_lyrics = gql`
        query FetchSongLyrics {
            song_lyrics {
                id,
                name,
                updated_at,
                is_original,
                is_published,
                is_approved_by_author
            }
        }`;

const delete_song_lyric = gql`
  mutation DeleteSongLyric ($id: Int!) {
    delete_song_lyric(id: $id) {
      id
    }
  }`;

export default {
  data() {
    return {
      headers: [
        { text: 'Název písničky', value: 'name' },
        { text: 'Typ', value: 'is_original' },
        { text: 'Naposledy upraveno', value: 'updated_at' },
        { text: 'Publikováno', value: 'is_published' },
        { text: 'Schváleno autorem', value: 'is_approved_by_author' },
        { text: 'Akce', value: 'action' },
      ],
      search_string: ""
    }
  },

  apollo: {
    song_lyrics: fetch_song_lyrics
  },

  methods: {
    askForm(id) {
      if (confirm('Opravdu chcete smazat daný záznam?')) {
        this.deleteSong(id);
      }
    },

    deleteSong(id) {
      this.$apollo.mutate({
        mutation: delete_song_lyric,
        variables: {id: id},
        refetchQueries: [{
          query: fetch_song_lyrics
        }]
      }).then((result) => {
        console.log('uspesne vymazano');
      }).catch((error) => {
        console.log('error');
      });
    },

    formFilter(val, search) {
      // console.log(this.removeDiacritics(search));
      if (typeof(val) == 'string') {
        let hay = removeDiacritics(val).toLowerCase();
        let needle = removeDiacritics(search).toLowerCase();

        console.log(hay);

        return hay.indexOf(needle) >= 0;

        // return this.removeDiacritics(val).indexOf(this.removeDiacritics(search)) >= 0;
      }

      return false;
    }
  }
}
</script>
