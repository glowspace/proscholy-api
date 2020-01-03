<template>
<!-- v-app must wrap all the components -->
  <v-app>
    <notifications/>
    <v-container fluid grid-list-xs>
    <v-radio-group v-model="createmodel_class" class="pt-0 mt-0">
      <v-radio
        label="Píseň určená pro Zpevnik.proscholy.cz + Regenschori.cz"
        :value="'SongLyric'"
      ></v-radio>
      <v-radio
        label="Píseň pouze pro Regenschori.cz"
        :value="'SongLyric--Regenschori'"
      ></v-radio>
    </v-radio-group>
    <create-model 
        :class-name="createmodel_class"
        label="Zadejte jméno nové písně"
        success-msg="Píseň úspěšně vytvořena"
        @saved="$apollo.queries.song_lyrics.refetch()"></create-model>
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
            :custom-filter="customFilter"
            :rows-per-page-items='[10,25,{"text":"Vše","value":-1}]'
            class="users-list">
            
            <template v-slot:items="props">
              <td>
                <a :href="'/admin/song/' + props.item.id + '/edit'">{{ props.item.name }}</a>
              </td>
              <td>
                <span v-if="props.item.type === 0">Originál</span>
                <span v-if="props.item.type === 1">Překlad</span>
                <span v-if="props.item.type === 2">Autorizovaný překlad</span>
              </td>
              <td>{{ props.item.authors.map(a => a.name).join(", ") || (props.item.has_anonymous_author ? "(anonymní)" : "-")}}</td>
              <td>{{ props.item.updated_at }}</td>
              <td>
                <span v-if="props.item.is_published">Ano</span>
                <span v-else>Ne</span>
              </td>
              <td>
                <span v-if="props.item.only_regenschori">jen R</span>
                <span v-else>R + PS</span>
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

import removeDiacritics from 'Admin/helpers/removeDiacritics';
import CreateModel from 'Admin/components/CreateModel.vue';

const fetch_items = gql`
        query FetchSongLyrics($has_lyrics: Boolean, $has_authors: Boolean, $has_chords: Boolean, $has_tags: Boolean) {
            song_lyrics(
              has_lyrics: $has_lyrics, 
              has_authors: $has_authors, 
              has_chords: $has_chords,
              has_tags: $has_tags
          ) {
                id
                name
                updated_at
                type
                is_published
                authors {
                  name
                }
                only_regenschori
                has_anonymous_author
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

  components: {
    CreateModel
  },

  data() {
    return {
      headers: [
        { text: 'Název písničky', value: 'name' },
        { text: 'Typ', value: 'type' },
        { text: 'Autoři', value: 'only_regenschori' },
        { text: 'Naposledy upraveno', value: 'updated_at' },
        { text: 'Publikováno', value: 'is_published' },
        { text: 'Zveřejnění', value: 'only_regenschori' },
        { text: 'Akce', value: 'action' },
      ],
      search_string: "",
      createmodel_class: "SongLyric"
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
      },
      result(result) {
        this.buildSearchIndex();
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

    buildSearchIndex() {
      for (var item of this.song_lyrics) {
        const authors = item.authors.map(a => a.name).join(" ") || (item.has_anonymous_author ? "anonymni" : "");
        const types = ["original", "preklad", "autorizovany preklad"];
        const str = removeDiacritics([item.name, types[item.type], authors].join(" ")).toLowerCase();

        this.$set(item, "search_index", str);
      }
    },

    customFilter(items, search) {
      const needle = removeDiacritics(search).toLowerCase();

      return items.filter(item => item.search_index.indexOf(needle) !== -1);
    },
  }
}
</script>
