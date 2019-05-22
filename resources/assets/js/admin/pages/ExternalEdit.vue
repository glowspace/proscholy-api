<template>
  <v-app>
    <notifications/>
    <v-container fluid grid-list-xs>
      <v-layout row>
        <v-flex xs12 md6>
          <v-form ref="form">
            <v-text-field
              label="Url odkaz"
              required
              v-model="model.url"
              data-vv-name="input.url"
              :error-messages="errors.collect('input.url')"
            ></v-text-field>
            <v-select :items="type_values" v-model="model.type" label="Typ"></v-select>
            <items-combo-box
                  v-bind:p-items="authors"
                  v-model="model.authors"
                  label="Autoři"
                  header-label="Vyberte autora z nabídky nebo vytvořte nového"
                  create-label="Potvrďte enterem a vytvořte nového autora"
                  :multiple="true"
                  :enable-custom="true"
                ></items-combo-box>
            <items-combo-box
              v-bind:p-items="song_lyrics"
              v-model="model.song_lyric"
              label="Píseň"
              header-label="Vyberte píseň"
              :multiple="false"
              :enable-custom="false"></items-combo-box>
          </v-form>
        </v-flex>
        <v-flex xs12 md6>
          <external-view v-if="model_database"
            :url="model_database.url" 
            :type="model_database.type" 
            :thumbnail-url="model_database.thumbnail_url" 
            :media-id="model_database.media_id">
          </external-view>
        </v-flex>
      </v-layout>
      <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
      <v-btn v-if="model.song_lyric" :disabled="isDirty" @click="goToAdminPage('song/' + model.song_lyric.id + '/edit')">Přejít na editaci písničky
      </v-btn>
      <v-btn v-if="model.song_lyric" :disabled="isDirty" @click="showSong()">Zobrazit píseň ve zpěvníku</v-btn>
      <br><br>
      <delete-model-dialog class-name="External" :model-id="model.id" @deleted="is_deleted = true" delete-msg="Opravdu chcete vymazat tento externí odkaz?">Vymazat</delete-model-dialog>
      <!-- model deleted dialog -->
      <v-dialog v-model="is_deleted" persistent max-width="320">
        <v-card>
          <v-card-title class="headline">Externí odkaz byl vymazán</v-card-title>
          <v-card-text>Externí odkaz byl vymazán z databáze.</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" flat @click="goToAdminPage('external')">Přejít na seznam externích odkazů</v-btn>
            <br>
            <v-btn color="green darken-1" flat @click="goToAdminPage('song/' + model.song_lyric.id + '/edit')">Přejít na editaci písně</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </v-app>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";
import fragment from "@/graphql/client/external_fragment.graphql";
import ItemsComboBox from "../components/ItemsComboBox.vue";
import DeleteModelDialog from "../components/DeleteModelDialog.vue";
import ExternalView from "../../components/ExternalView.vue";

const FETCH_MODEL_DATABASE = gql`
  query($id: ID!) {
    model_database: external(id: $id) {
      ...ExternalFillableFragment
      type_string_values
    }
  }
  ${fragment}
`;

const MUTATE_MODEL_DATABASE = gql`
  mutation($input: UpdateExternalInput!) {
    update_external(input: $input) {
      ...ExternalFillableFragment
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

const FETCH_SONG_LYRICS= gql`
  query {
    song_lyrics {
      id
      name
    }
  }
`;

export default {
  props: ["preset-id"],
  components: {
    ItemsComboBox,
    DeleteModelDialog,
    ExternalView
  },

  data() {
    return {
      model: {
        // here goes the definition of model attributes
        // should match the definition in its ModelFillableFragment in (see graphql/client/model_fragment.graphwl)
        id: undefined,
        url: undefined,
        type: undefined,
        authors: [],
        song_lyric: undefined
      },
      type_values: [],
      is_deleted: false
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
        let external = result.data.model_database;
        // load the requested fields to the vue data.model property
        for (let field of this.getFieldsFromFragment(false)) {
          Vue.set(this.model, field, external[field]);
        }

        this.type_values = external.type_string_values.map((val, index) => {
          return { value: index, text: val };
        });
      }
    },
    authors: {
      query: FETCH_AUTHORS,
    },
    song_lyrics: {
      query: FETCH_SONG_LYRICS,
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

      if (!this.model.url) return true;

      for (let field of this.getFieldsFromFragment(this)) {
        if (!_.isEqual(this.model[field], this.model_database[field])){
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
              url: this.model.url,
              type: this.model.type,
              song_lyric: this.getModelToSyncBelongsTo(this.model.song_lyric),
              authors: {
                create: this.getModelsToCreateBelongsToMany(this.model.authors),
                sync: this.getModelsToSyncBelongsToMany(this.model.authors)
              }
            }
          }
        })
        .then(result => {
          this.$validator.errors.clear();
          this.$notify({
            title: "Úspěšně uloženo :)",
            text: "Externí odkaz byl úspěšně uložen",
            type: "success"
          });
        })
        .catch(error => {
          if (error.graphQLErrors.length == 0) {
            // unknown error happened
            this.$notify({
              title: "Chyba při ukládání",
              text: "Externí odkaz nebyl uložen",
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

    // helper method to load field names defined in fragment graphql definition
    getFieldsFromFragment(includeId) {
      let fieldDefs = fragment.definitions[0].selectionSet.selections;
      let fieldNames = fieldDefs.map(field => {
        return field.name.value;
      });

      if (!includeId)
        fieldNames = fieldNames.filter(field => {
          return field != "id";
        });

      return fieldNames;
    },

    getModelsToCreateBelongsToMany(models){
      return models.filter(model => {
        if(model.id) return false;
        return true;
      });
    },

    getModelsToSyncBelongsToMany(models){
      return models.filter(model => {
        if(model.id) return true;
        return false;
      }).map(model => {
        return model.id
      });
    },

    getModelToSyncBelongsTo(model) {
      let obj = {};

      if (model) {
        obj.update = {
          id: model.id
        }
      } else {
        obj.disconnect = true;
      }
      
      return obj;
    },

    async goToPage(url, save=true) {
      if (this.isDirty && save)
        await this.submit();

      setTimeout(() => {
        if (!this.isDirty && save) {
          var base_url = document.querySelector('#baseUrl').getAttribute('value');
          window.location.href = base_url + '/' + url;
        }
      }, 500);
    },

    goToAdminPage(url, save=true) {
      this.goToPage('/admin/' + url, save);
    },

    showSong() {
      window.location.href = this.model_database.song_lyric.public_url;
    },
  },
};
</script>
