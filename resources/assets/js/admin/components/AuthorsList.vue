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
            :items="authors"
            :search="search_string"
            :filter="formFilter"
            >
            
            <template v-slot:items="props">
              <td>
                <a :href="'/admin/author/' + props.item.id + '/edit'">{{ props.item.name }}</a>
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
</style>fetch_items

<script>

import gql from 'graphql-tag';

import removeDiacritics from '../helpers/removeDiacritics';

const fetch_items = gql`
        query FetchAuthors {
            authors {
                id,
                name,
                type_string
            }
        }`;

const delete_item = gql`
  mutation DeleteAuthor ($id: Int!) {
    delete_author(id: $id) {
      id
    }
  }`;

export default {
  props: ['is-todo'],

  data() {
    return {
      headers: [
        { text: 'Jméno', value: 'name' },
        { text: 'Typ', value: 'type_string' },
        { text: 'Akce', value: 'action' }
      ],
      search_string: ""
    }
  },

  apollo: {
    authors: { 
      query: fetch_items,
      variables() {
        return { 
          is_todo: this.isTodo,
        }
      }
    }
  },

  methods: {
    askForm(id) {
      if (confirm('Opravdu chcete smazat daný záznam?')) {
        this.deleteAuthor(id);
      }
    },

    deleteAuthor(id) {
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
