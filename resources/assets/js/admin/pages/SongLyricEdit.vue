<template>
  <v-app>
    <notifications/>
    <v-container grid-list-xs>
      <v-layout row>
        <v-flex xs12 md6>
          <v-form ref="form">
            <v-text-field
              label="Název písně"
              required
              v-model="model.name"
              data-vv-name="input.name"
              :error-messages="errors.collect('input.name')"
            ></v-text-field>
            <!-- <v-select :items="type_values" v-model="model.type" label="Typ"></v-select> -->
            <items-combo-box
              v-bind:p-items="authors"
              v-model="model.authors"
              label="Autoři"
              create-label="Vyberte autora z nabídky nebo vytvořte novou"
              :multiple="true"></items-combo-box>
            <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
          </v-form>
        </v-flex>
        <v-flex xs12 md6></v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";
import fragment from "@/graphql/client/song_lyric_fragment.graphql";
import ItemsComboBox from "../components/ItemsComboBox.vue"

const FETCH_MODEL_DATABASE = gql`
  query($id: ID!) {
    model_database: song_lyric(id: $id) {
      ...SongLyricFillableFragment
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

export default {
  props: ["preset-id"],
  components: {
    ItemsComboBox
  },

  data() {
    return {
      model: {
        // here goes the definition of model attributes
        // should match the definition in its ModelFillableFragment in (see graphql/client/model_fragment.graphwl)
        id: undefined,
        name: undefined,
        authors: [],
      },
      type_values: [],
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
          Vue.set(this.model, field, song_lyric[field]);
        }

        // this.type_values = song_lyric.type_string_values.map((val, index) => {
        //   return { value: index, text: val };
        // });
      }
    },
    authors: {
      query: FETCH_AUTHORS,
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
              name: this.model.name,
              // type: this.model.type,
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
    }
  },
};
</script>
