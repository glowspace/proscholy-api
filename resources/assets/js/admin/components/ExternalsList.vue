<template>
<!-- v-app must wrap all the components -->
  <v-app>
    <v-container grid-list-xs>
      <v-layout row>
        <v-flex xs5 offset-xs7 md3 offset-md9>
          <v-text-field v-model="search_string" label="Vyhledávání"></v-text-field>
        </v-flex>
      </v-layout>
      <v-layout row>
        <v-flex xs12>
          <v-data-table
            :headers="headers"
            :items="externals"
            :search="search_string"
            :filter="formFilter"
            >
            
            <template v-slot:items="props">
              <td>
                <a :href="'/admin/external/' + props.item.id + '/edit'">{{ props.item.public_name }}</a>
              </td>
              <td>{{ props.item.type_string }}</td>
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
        query FetchExternals {
            externals {
                id,
                public_name,
                type_string
            }
        }`;

const delete_item = gql`
  mutation DeleteExternal ($id: Int!) {
    delete_external(id: $id) {
      id
    }
  }`;

export default {
  // props: ['has-lyrics', 'has-authors', 'has-chords', 'has-tags'],

  data() {
    return {
      headers: [
        { text: 'Název', value: 'public_name' },
        { text: 'Typ', value: 'type_string' },
        { text: 'Akce', value: 'action' }
      ],
      search_string: ""
    }
  },

  apollo: {
    externals: { 
      query: fetch_items,
      variables() {
        return { 
          // has_lyrics: this.hasLyrics,
          // has_authors: this.hasAuthors,
          // has_chords: this.hasChords,
          // has_tags: this.hasTags
        }
      }
    }
  },

  methods: {
    askForm(id) {
      if (confirm('Opravdu chcete smazat daný záznam?')) {
        this.deleteExternal(id);
      }
    },

    deleteExternal(id) {
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
