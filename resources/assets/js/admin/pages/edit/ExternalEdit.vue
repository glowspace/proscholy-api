<template>
  <v-app>
    <notifications/>
    <v-container fluid grid-list-xs>
      <v-layout row wrap>
        <v-flex xs12 md6>
          <v-form ref="form">
            <v-text-field
              label="Url odkaz"
              required
              v-model="model.url"
              data-vv-name="input.url"
              :error-messages="errors.collect('input.url')"
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
              :enable-custom="false"
            ></items-combo-box>
            <items-combo-box
                v-bind:p-items="tags_instrumentation"
                v-model="model.tags_instrumentation"
                label="Instrumentace"
                header-label="Vyberte štítek z nabídky nebo vytvořte nový"
                create-label="Potvrďte enterem a vytvořte nový štítek"
                :multiple="true"
                :enable-custom="true"
              ></items-combo-box>
          </v-form>
        </v-flex>
        <v-flex xs12 md6>
          <external-view
            v-if="model_database"
            :url="model_database.url"
            :type="model_database.type"
            :thumbnail-url="model_database.thumbnail_url"
            :media-id="model_database.media_id"
          ></external-view>
        </v-flex>
      </v-layout>
      <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
      <v-btn
        v-if="model.song_lyric"
        :disabled="isDirty"
        @click="goToAdminPage('song/' + model.song_lyric.id + '/edit')"
      >Přejít na editaci písničky</v-btn>
      <v-btn
        v-if="model.song_lyric"
        :disabled="isDirty"
        @click="showSong()"
      >Zobrazit píseň ve zpěvníku</v-btn>
      <br>
      <br>
      <delete-model-dialog
        class-name="External"
        :model-id="model.id || null"
        @deleted="is_deleted = true"
        delete-msg="Opravdu chcete vymazat tento externí odkaz?"
      >Vymazat</delete-model-dialog>
      <!-- model deleted dialog -->
      <v-dialog v-model="is_deleted" persistent max-width="320">
        <v-card>
          <v-card-title class="headline">Externí odkaz byl vymazán</v-card-title>
          <v-card-text>Externí odkaz byl vymazán z databáze.</v-card-text>
          <v-card-actions class="d-flex flex-column justify-content-end">
            <v-spacer></v-spacer>
            <div>
              <v-btn
                color="green darken-1"
                flat
                @click="goToAdminPage('external', false)"
              >Přejít na seznam externích odkazů</v-btn>
            </div>
            <div>
              <v-btn v-if="model.song_lyric"
                color="green darken-1"
                flat
                @click="goToAdminPage('song/' + model.song_lyric.id + '/edit', false)"
              >Přejít na editaci písně</v-btn>
            </div>
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

import EditForm from './EditForm';
import External from 'Admin/models/External';

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
      name: rich_name
    }
  }
`;

const FETCH_TAGS_INSTRUMENTATION = gql`
  query {
    tags_instrumentation: tags(type: 50) {
      id
      name
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
        url: undefined,
        type: undefined,
        authors: [],
        song_lyric: undefined,
        tags_instrumentation: []
      },
      enums: {
        type: []
      },
      fragment: External.fragment,
      is_deleted: false
    };
  },

  apollo: {
    model_database: {
      query: External.QUERY,
      variables() {
        return External.getQueryVariables(this.model)
      },

      result(result) {
        this.loadModelDataFromResult(result);
        this.loadEnumJsonFromResult(result, "type_string_values", this.enums.type);
      }
    },
    authors: {
      query: FETCH_AUTHORS
    },
    song_lyrics: {
      query: FETCH_SONG_LYRICS
    },
    tags_instrumentation: {
      query: FETCH_TAGS_INSTRUMENTATION
    },
  },

  // computed: {
  //   isDirty() {
  //     if (this.is_deleted) return false;
  //     if (!this.model_database) return false;
  // todo:                     if (!this.model.url) return true;

  //     for (let field of this.getFieldsFromFragment(this)) {
  //       if (!_.isEqual(this.model[field], this.model_database[field])) {
  //         return true;
  //       }
  //     }

  //     return false;
  //   }
  // },

  methods: {
    submit() {
      this.$apollo
        .mutate({
          mutation: External.MUTATION,
          variables: External.getMutationVariables(this.model)
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

          this.handleValidationErrors(error);
        });
    },

    showSong() {
      window.location.href = this.model_database.song_lyric.public_url;
    }
  }
};
</script>
