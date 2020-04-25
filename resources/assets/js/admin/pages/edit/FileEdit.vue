<template>
  <v-app>
    <notifications/>
    <v-container fluid grid-list-xs>
      <v-layout row wrap>
        <v-flex xs12 md6>
          <v-form ref="form">
            <v-text-field
              label="Zobrazovaný název"
              :placeholder="'(stejný jako jméno souboru - ' + model.filename + ')'"
              required
              v-model="model.name"
              data-vv-name="input.name"
              :error-messages="errors.collect('input.name')"
            ></v-text-field>

            <v-text-field
              label="Jméno souboru po stáhnutí"
              required
              v-model="model.filename"
              data-vv-name="input.filename"
              :error-messages="errors.collect('input.filename')"
            ></v-text-field>

            <v-select :items="enums.type" v-model="model.type" label="Typ"></v-select>
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
            :type="model_database.external_type">
          </external-view>
        </v-flex>
      </v-layout>
      <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
      <v-btn v-if="model.song_lyric" :disabled="isDirty" @click="goToAdminPage('song/' + model.song_lyric.id + '/edit')">Přejít na editaci písničky
      </v-btn>
      <v-btn v-if="model.song_lyric" :disabled="isDirty" @click="showSong()">Zobrazit píseň ve zpěvníku</v-btn>
      <br><br>
      <delete-model-dialog class-name="File" :model-id="model.id" @deleted="is_deleted = true" delete-msg="Opravdu chcete vymazat tento soubor?">Vymazat</delete-model-dialog>
      <!-- model deleted dialog -->
      <v-dialog v-model="is_deleted" persistent max-width="320">
        <v-card>
          <v-card-title class="headline">Soubor byl vymazán</v-card-title>
          <v-card-text>Soubor byl vymazán z databáze.</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" flat @click="goToAdminPage('file')">Přejít na seznam souborů</v-btn>
            <br>
            <v-btn color="green darken-1" flat @click="goToAdminPage('song/' + model.song_lyric.id + '/edit')">Přejít na editaci písně</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </v-app>
</template>

<script>
import gql from "graphql-tag";
import ItemsComboBox from "Admin/components/ItemsComboBox.vue";
import DeleteModelDialog from "Admin/components/DeleteModelDialog.vue";
import ExternalView from "Public/components/ExternalView.vue";
import EditForm from "./EditForm";

import File from "Admin/models/File";

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
      name: rich_name
    }
  }
`;

export default {
  components: {
    ItemsComboBox,
    DeleteModelDialog,
    ExternalView
  },
  extends: EditForm,

  data() {
    return {
      model: {
        // here goes the definition of model attributes
        id: undefined,
        type: undefined,
        name: "",
        filename: "",
        authors: [],
        song_lyric: undefined
      },
      enums: {
        type: []
      },
      is_deleted: false,
      fragment: File.fragment
    };
  },

  apollo: {
    model_database: {
      query: File.QUERY,
      variables() {
        return File.getQueryVariables(this.model);
      },
      result(result) {
        this.loadModelDataFromResult(result);
        this.loadEnumJsonFromResult(result, "type_string_values", this.enums.type);
      }
    },
    authors: {
      query: FETCH_AUTHORS,
    },
    song_lyrics: {
      query: FETCH_SONG_LYRICS,
    }
  },

  methods: {
    submit() {
      this.$apollo
        .mutate({
          mutation: File.MUTATION,
          variables: File.getMutationVariables(this.model)
        })
        .then(result => {
          this.$validator.errors.clear();
          this.$notify({
            title: "Úspěšně uloženo :)",
            text: "Soubor byl úspěšně uložen",
            type: "success"
          });
        })
        .catch(error => {
          if (error.graphQLErrors.length == 0) {
            // unknown error happened
            this.$notify({
              title: "Chyba při ukládání",
              text: "Soubor nebyl uložen",
              type: "error"
            });
            return;
          }

          this.handleValidationErrors(error);
        });
    },

    showSong() {
      window.location.href = this.model_database.song_lyric.public_url;
    },
  },
};
</script>
