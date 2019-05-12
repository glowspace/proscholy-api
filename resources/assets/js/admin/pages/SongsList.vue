<template>
<!-- v-app must wrap all the components -->
  <v-app>
    <v-container fluid grid-list-xs>
      <v-layout row>
        <v-flex xs5 offset-xs7 md3 offset-md9>
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
            :rows-per-page-items='[10,25,{"text":"Vše","value":-1}]'
            class="users-list">
            
            <template v-slot:items="props">
              <td>
                <a :href="'/admin/song/' + props.item.id">{{ props.item.name }}</a>
              </td>
              <td>
                <span v-if="props.item.type === 0">Originál</span>
                <span v-if="props.item.type === 1">Překlad</span>
                <span v-if="props.item.type === 2">Autorizovaný překlad</span>
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
                <a href="#" style="color:red" v-on:click="askForm(props.item.id)">Vymazat</a>
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

const fetch_items = gql`
        query FetchSongLyrics($has_lyrics: Boolean, $has_authors: Boolean, $has_chords: Boolean, $has_tags: Boolean) {
            song_lyrics(
              has_lyrics: $has_lyrics, 
              has_authors: $has_authors, 
              has_chords: $has_chords,
              has_tags: $has_tags
          ) {
                id,
                name,
                updated_at,
                type,
                is_published,
                is_approved_by_author
            }
        }`;

const delete_item = gql`
  mutation DeleteSongLyric ($id: ID!) {
    delete_song_lyric(id: $id) {
      id
    }
  }`;
  
export default {
  props: ['has-lyrics', 'has-authors', 'has-chords', 'has-tags'],

  data() {
    return {
      headers: [
        { text: 'Název písničky', value: 'name' },
        { text: 'Typ', value: 'type' },
        { text: 'Naposledy upraveno', value: 'updated_at' },
        { text: 'Publikováno', value: 'is_published' },
        { text: 'Schváleno autorem', value: 'is_approved_by_author' },
        { text: 'Akce', value: 'action' },
      ],
      search_string: ""
    }
  },

  apollo: {
    song_lyrics: { 
      query: fetch_items,
      variables() {
        return { 
          has_lyrics: this.hasLyrics,
          has_authors: this.hasAuthors,
          has_chords: this.hasChords,
          has_tags: this.hasTags
        }
      }
    }
  },

  methods: {
    askForm(id) {
      if (confirm('Opravdu chcete smazat daný záznam?')) {
        this.deleteSong(id);
      }
    },

    deleteSong(id) {
      this.$apollo.mutate({
        mutation: delete_item,
        variables: {id: id},
        refetchQueries: [{
          query: fetch_items
        }]
      }).then((result) => {
        console.log('uspesne vymazano');
      }).catch((error) => {
        console.log('error');
      });
    },

    formFilter(val, search)
    {
      if (typeof(val) == 'string') {
        let hay = removeDiacritics(val).toLowerCase();
        let needle = removeDiacritics(search).toLowerCase();

        return hay.indexOf(needle) >= 0;
      }

      return false;
    }
  }
}
</script>
