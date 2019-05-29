<template>
<!-- v-app must wrap all the components -->
  <v-app>
    <notifications/>
    <v-container fluid grid-list-xs>
      <create-model 
        class-name="External"
        label="Zadejte adresu nového externího odkazu"
        success-msg="Externí odkaz úspěšně vytvořen"
        @saved="$apollo.queries.externals.refetch()"
        :force-edit="true"></create-model>
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
            :rows-per-page-items='[10,25,{"text":"Vše","value":-1}]'
            >
            
            <template v-slot:items="props">
              <td>
                <a :href="'/admin/external/' + props.item.id + '/edit'">{{ getShortUrl(props.item.url) }}</a>
              </td>
              <td>{{ props.item.type_string }}</td>
              <td>{{ props.item.song_lyric ? props.item.song_lyric.name : "-" }}</td>
              <td>{{ props.item.authors.map(a => a.name).join(", ") || "-"}}</td>
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

import removeDiacritics from 'Admin/helpers/removeDiacritics';
import CreateModel from "Admin/components/CreateModel.vue"

const fetch_items = gql`
        query FetchExternals ($is_todo: Boolean) {
            externals (is_todo: $is_todo) {
                id
                url
                type_string
                song_lyric {
                  name
                }
                authors {
                  name
                }
            }
        }`;

const delete_item = gql`
  mutation DeleteExternal ($id: ID!) {
    delete_external(id: $id) {
      id
    }
  }`;

export default {
  props: ['is-todo'],

  components: {
    CreateModel
  },

  data() {
    return {
      headers: [
        { text: 'Adresa', value: 'url' },
        { text: 'Typ', value: 'type_string' },
        { text: 'Píseň', value: 'song_lyric' },
        { text: 'Autoři', value: 'authors' },
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
          is_todo: this.isTodo,
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
    },

    getShortUrl(url)
    {
      if (!url) return;

      const bare = url.replace("http://", "").replace("https://", "").replace("www.", "");
      if (bare.length < 50) return bare;

      const head = bare.substring(0, 15);
      const tail = bare.substring(bare.length - 15, bare.length);

      return head + "..." + tail;
    }
  }
}
</script>
