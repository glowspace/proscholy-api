<template>
  <v-app>
    <notifications/>
    <v-container fluid grid-list-xs>
      <v-layout row>
        <v-flex xs12 md6>
          <v-form ref="form">
            <v-text-field
              label="Jméno zpěvníku"
              required
              v-model="model.name"
              data-vv-name="input.name"
              :error-messages="errors.collect('input.name')"
            ></v-text-field>

            <v-text-field
              label="Zkratka"
              v-model="model.shortcut"
              data-vv-name="input.shortcut"
              :error-messages="errors.collect('input.shortcut')"
            ></v-text-field>

          </v-form>
        </v-flex>
        <v-flex xs12 md6 class="edit-description">
          <h5>Seznam písní ve zpěvníku</h5>
          <!-- <v-btn
            v-for="(record, index) in model.records"
            v-bind:key="index"
            class="text-none"
          >{{ record.song_lyric.name }}</v-btn> -->
          <v-data-table
            :headers="records_headers"
            :items="model.records"
            class=""
          >
            <template v-slot:items="props">
              <td>{{ props.item.number }}</td>
              <td>{{ props.item.song_lyric.name }}</td>
            </template>
          </v-data-table>

        </v-flex>
      </v-layout>
      <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
      <!-- <v-btn @click="show" :disabled="isDirty">Zobrazit ve zpěvníku</v-btn> -->
      <br><br>
      <delete-model-dialog class-name="Songbook" :model-id="model.id" @deleted="is_deleted = true" delete-msg="Opravdu chcete vymazat tento zpěvník?">Vymazat</delete-model-dialog>
      <!-- model deleted dialog -->
      <v-dialog v-model="is_deleted" persistent max-width="290">
        <v-card>
          <v-card-title class="headline">Zpěvník byl vymazán</v-card-title>
          <v-card-text>Zpěvník byl vymazán z databáze.</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" flat @click="goToAdminPage('songbook')">Přejít na seznam zpěvníků</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </v-app>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";
import fragment from "Fragments/songbook_fragment.graphql";
import ItemsComboBox from "Admin/components/ItemsComboBox.vue";
import DeleteModelDialog from "Admin/components/DeleteModelDialog.vue";

const FETCH_MODEL_DATABASE = gql`
  query($id: ID!) {
    model_database: songbook(id: $id) {
      ...SongbookFillableFragment
    }
  }
  ${fragment}
`;

const MUTATE_MODEL_DATABASE = gql`
  mutation($input: UpdateSongbookInput!) {
    update_songbook(input: $input) {
      ...SongbookFillableFragment
    }
  }
  ${fragment}
`;

// const FETCH_AUTHORS = gql`
//   query { 
//     songbooks(type: 0) {
//       id
//       name
//     }
//   }
// `;


export default {
  props: ["preset-id"],

  components: {
    ItemsComboBox,
    DeleteModelDialog
  },

  data() {
    return {
      model: {
        // here goes the definition of model attributes
        // should match the definition in its ModelFillableFragment in (see graphql/client/model_fragment.graphwl)
        id: undefined,
        name: undefined,
        shortcut: undefined,
        records: []
      },
      is_deleted: false,
      records_headers: [
        { text: 'Číslo', value: 'number' },
        // { text: 'Typ', value: 'type_string' },
        { text: 'Jméno', value: 'name' }
      ],
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
        let songbook = result.data.model_database;
        // load the requested fields to the vue data.model property
        for (let field of this.getFieldsFromFragment(false)) {
          Vue.set(this.model, field, songbook[field]);
        }
      }
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
              shortcut: this.model.shortcut
            }
          }
        })
        .then(result => {
          this.$validator.errors.clear();
          this.$notify({
            title: "Úspěšně uloženo :)",
            text: "Zpěvník byl úspěšně uložen",
            type: "success"
          });
        })
        .catch(error => {
          if (error.graphQLErrors.length == 0) {
            // unknown error happened
            this.$notify({
              title: "Chyba při ukládání",
              text: "Zpěvník nebyl uložen",
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

    // getModelsToCreateBelongsToMany(models){
    //   return models.filter(model => {
    //     if(model.id) return false;
    //     return true;
    //   });
    // },

    // getModelsToSyncBelongsToMany(models){
    //   return models.filter(model => {
    //     if(model.id) return true;
    //     return false;
    //   }).map(model => {
    //     return model.id
    //   });
    // },

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

    show() {
      var base_url = document.querySelector("#baseUrl").getAttribute("value");
      window.location.href = base_url + "/autor/" + this.model.id;
    }
  }
};
</script>
