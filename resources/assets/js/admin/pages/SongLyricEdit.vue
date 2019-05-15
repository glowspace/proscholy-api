<template>
  <v-app>
    <notifications/>
    <v-container fluid grid-list-xs>
      <v-tabs  color="transparent">
        <v-tab>Údaje o písni</v-tab>
        <v-tab>Text</v-tab>
        <v-tab-item>
          <v-layout row pt-2>
            <v-flex xs12 md6>
              <v-form ref="form">
                <v-text-field
                  label="Název písně"
                  required
                  v-model="model.name"
                  data-vv-name="input.name"
                  :error-messages="errors.collect('input.name')"
                  v-on:input="onNameChange"
                ></v-text-field>
                <items-combo-box
                  v-bind:p-items="authors"
                  v-model="model.authors"
                  label="Autoři"
                  create-label="Vyberte autora z nabídky nebo vytvořte nového"
                  :multiple="true"
                ></items-combo-box>
                <v-checkbox
                  v-model="model.has_anonymous_author"
                  label="Anonymní autor (nezobrazovat v to-do)"
                ></v-checkbox>

                <div v-if="model.song && model_database.song" class="mb-3">
                  <song-lyrics-group v-model="model.song.song_lyrics" :edit-id="model.id"></song-lyrics-group>

                  <v-btn
                    color="error"
                    outline
                    @click="resetGroup"
                    v-if="model.song.song_lyrics.length > 1"
                  >Odstranit píseň ze skupiny</v-btn>
                  <select-song-group-dialog
                    outline
                    v-if="model_database.song.song_lyrics.length == 1 &&
                model.song.song_lyrics.length == 1"
                    v-on:submit="addToGroup"
                  ></select-song-group-dialog>
                </div>

                <items-combo-box
                  v-bind:p-items="tags_unofficial"
                  v-model="model.tags_unofficial"
                  label="Štítky"
                  create-label="Vyberte štítek z nabídky nebo vytvořte nový"
                  :multiple="true"
                ></items-combo-box>
                <items-combo-box
                  v-bind:p-items="tags_official"
                  v-model="model.tags_official"
                  label="Liturgie"
                  create-label="Vyberte část liturgie z nabídky"
                  :multiple="true"
                ></items-combo-box>
              </v-form>
            </v-flex>
            <v-flex xs12 md6></v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item>
          <v-layout row>
            <v-flex xs12 md6>
              <v-select :items="lang_values" v-model="model.lang" label="Jazyk"></v-select>
                <a
                  id="file_select"
                  class="btn btn-primary"
                  v-on:click="$refs.fileinput.click()"
                >Nahrát ze souboru OpenSong</a>
                <input type="file" class="d-none" ref="fileinput" v-on:change="handleOpensongFile">
                <v-textarea auto-grow outline name="input-7-4" label="Text" v-model="model.lyrics"></v-textarea>
            </v-flex>
            <v-flex xs12 md6>
              <!-- externals and files view -->
              <!-- <p v-for="external in model.externals" v-bind:key="external.id">{{ external.public_name }}</p> -->
              <!-- <p v-for="file in model.files" v-bind:key="file.id">{{ file.public_name }}</p> -->
              <img v-for="external in model.externals" v-bind:key="external.id"
                  v-bind:src="external.thubmnail_url"
                  class="grey lighten-2"
                />
            </v-flex>
          </v-layout>
        </v-tab-item>
      </v-tabs>
      <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
      <v-btn @click="reset" :disabled="!isDirty">Vrátit změny</v-btn>
      <v-btn @click="show" :disabled="isDirty">Zobrazit ve zpěvníku</v-btn>
    </v-container>
  </v-app>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";
import fragment from "@/graphql/client/song_lyric_fragment.graphql";
import ItemsComboBox from "../components/ItemsComboBox.vue";
import SongLyricsGroup from "../components/SongLyricsGroup.vue";
import SelectSongGroupDialog from "../components/SelectSongGroupDialog.vue";

const FETCH_MODEL_DATABASE = gql`
  query($id: ID!) {
    model_database: song_lyric(id: $id) {
      ...SongLyricFillableFragment
      lang_string_values
    }
  }
  ${fragment}
`;

const MUTATE_MODEL_DATABASE = gql`
  mutation($input: UpdateSongLyricInput!) {
    update_song_lyric(input: $input) {
      ...SongLyricFillableFragment
    }
  }
  ${fragment}
`;

const FETCH_AUTHORS = gql`
  query {
    authors {
      id
      name
    }
  }
`;

const FETCH_SONG_LYRICS = gql`
  query {
    song_lyrics {
      id
      name
    }
  }
`;

const FETCH_TAGS_UNOFFICIAL = gql`
  query {
    tags_unofficial: tags(type: 0) {
      id
      name
    }
  }
`;

const FETCH_TAGS_OFFICIAL = gql`
  query {
    tags_official: tags(type: 1) {
      id
      name
    }
  }
`;

export default {
  props: ["preset-id", "csrf"],
  components: {
    ItemsComboBox,
    SongLyricsGroup,
    SelectSongGroupDialog
  },

  data() {
    return {
      model: {
        // here goes the definition of model attributes
        // should match the definition in its ModelFillableFragment in (see graphql/client/model_fragment.graphwl)
        id: undefined,
        name: undefined,
        has_anonymous_author: undefined,
        lang: undefined,
        lyrics: undefined,
        tags_unofficial: [],
        tags_official: [],
        authors: [],
        externals: [],
        files: [],
        song: undefined
      },
      lang_values: []
    };
  },

  apollo: {
    model_database: {
      query: FETCH_MODEL_DATABASE,
      variables() {
        return {
          id: this.model.id
        };
      },
      result(result) {
        let song_lyric = result.data.model_database;
        // load the requested fields to the vue data.model property
        for (let field of this.getFieldsFromFragment(false)) {
          let clone = _.cloneDeep(song_lyric[field]);
          Vue.set(this.model, field, clone);
        }

        // lang string values are an associative array passed as JSON object
        let parsed_obj = JSON.parse(song_lyric.lang_string_values);

        for (const [key, value] of Object.entries(parsed_obj)) {
          this.lang_values.push({ value: key, text: value });
        }
      }
    },
    authors: {
      query: FETCH_AUTHORS
    },
    tags_official: {
      query: FETCH_TAGS_OFFICIAL
    },
    tags_unofficial: {
      query: FETCH_TAGS_UNOFFICIAL
    }
  },

  $_veeValidate: {
    validator: "new"
  },

  mounted() {
    this.model.id = this.presetId;

    // prevent user to leave the form if dirty
    window.onbeforeunload = e => {
      if (this.isDirty) {
        e.preventDefault();
        e.returnValue = "";
      }
    };
  },

  computed: {
    isDirty() {
      if (!this.model_database) return false;

      for (let field of this.getFieldsFromFragment(this)) {
        if (!_.isEqual(this.model[field], this.model_database[field])) {
          return true;
        }
      }

      return false;
    }
  },

  methods: {
    submit() {
      this.$apollo
        .mutate({
          mutation: MUTATE_MODEL_DATABASE,
          variables: {
            input: {
              id: this.model.id,
              name: this.model.name,
              lang: this.model.lang,
              has_anonymous_author: this.model.has_anonymous_author,
              lyrics: this.model.lyrics,
              song: this.model.song,
              authors: {
                create: this.getModelsToCreateBelongsToMany(this.model.authors),
                sync: this.getModelsToSyncBelongsToMany(this.model.authors)
              },
              tags_unofficial: {
                create: this.getModelsToCreateBelongsToMany(
                  this.model.tags_unofficial
                ),
                sync: this.getModelsToSyncBelongsToMany(
                  this.model.tags_unofficial
                )
              },
              tags_official: {
                // create: this.getModelsToCreateBelongsToMany(this.model.tags_official),
                sync: this.getModelsToSyncBelongsToMany(
                  this.model.tags_official
                )
              }
            }
          }
        })
        .then(result => {
          this.$validator.errors.clear();
          this.$notify({
            title: "Úspěšně uloženo :)",
            text: "Píseň byla úspěšně uložena",
            type: "success"
          });
        })
        .catch(error => {
          if (
            error.graphQLErrors.length == 0 ||
            error.graphQLErrors[0].extensions.validation === undefined
          ) {
            // unknown error happened
            this.$notify({
              title: "Chyba při ukládání",
              text: "Píseň nebyla uložena",
              type: "error"
            });
            return;
          }

          let errorFields = error.graphQLErrors[0].extensions.validation;

          // clear the old errors and (add new ones if exist)
          this.$validator.errors.clear();
          for (const [key, value] of Object.entries(errorFields)) {
            this.$validator.errors.add({ field: key, msg: value });
          }
        });
    },

    reset() {
      for (let field of this.getFieldsFromFragment(false)) {
        let clone = _.cloneDeep(this.model_database[field]);
        Vue.set(this.model, field, clone);
      }
    },

    show() {
      window.location.href = this.model_database.public_url;
    },

    // helper method to load field names defined in fragment graphql definition
    getFieldsFromFragment(includeId) {
      let fieldDefs = fragment.definitions[0].selectionSet.selections;
      let fieldNames = fieldDefs.map(field => {
        if (field.alias) return field.alias.value;
        return field.name.value;
      });

      if (!includeId)
        fieldNames = fieldNames.filter(field => {
          return field != "id";
        });

      return fieldNames;
    },

    getModelsToCreateBelongsToMany(models) {
      return models.filter(model => {
        if (model.id) return false;
        return true;
      });
    },

    getModelsToSyncBelongsToMany(models) {
      return models
        .filter(model => {
          if (model.id) return true;
          return false;
        })
        .map(model => {
          return model.id;
        });
    },

    getModelToSyncBelongsTo(model) {
      let obj = {};

      if (model) {
        obj.update = {
          id: model.id
        };
      } else {
        obj.disconnect = true;
      }

      return obj;
    },

    handleOpensongFile(e) {
      var file = e.target.files[0];

      var reader = new FileReader();
      reader.onload = e => {
        console.log("file loaded succesfully");

        $.post(
          "/api/parse/opensong",
          {
            file_contents: e.target.result,
            _token: this.csrf
          },
          data => {
            this.model.lyrics = data;
          }
        );
      };

      reader.readAsText(file);
    },

    resetGroup() {
      this.model.song.song_lyrics = this.model.song.song_lyrics.filter(
        song_lyric => {
          return song_lyric.id === this.model.id;
        }
      );
    },

    addToGroup(song) {
      // check if there is original in the group and then
      if (
        song.song_lyrics.filter(sl => {
          return sl.type == 0;
        }).length > 0
      )
        this.model.song.song_lyrics[0].type = 1;

      this.model.song.song_lyrics = this.model.song.song_lyrics.concat(
        song.song_lyrics
      );
    },

    onNameChange(name) {
      // update the corresponding name in song.song_lyrics
      for (var song_lyric of this.model.song.song_lyrics) {
        if (song_lyric.id == this.model.id) {
          Vue.set(song_lyric, "name", name);
        }
      }
    }
  }
};
</script>
