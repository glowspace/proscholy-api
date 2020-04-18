<template>
  <v-app>
    <notifications/>
    <v-container fluid grid-list-xs>
      <v-layout row wrap>
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

            <v-checkbox
                class="mt-0"
                v-model="model.is_private"
                label="Neveřejný zpěvník (pouze pro interní použití)"
              ></v-checkbox>
            
            <number-input
              label="Počet písní"
              v-model="model.songs_count"
              vv-name="input.songs_count"
              :min-value="0">
            </number-input>

            <p v-if="!model.songs_count">
              Aby bylo možné zde editovat všechny záznamy, je třeba zadat celkový počet písní.
              <br>Tento údaj zatím není třeba zadávat přesně.
            </p>

            <v-text-field
              label="Barva"
              v-model="model.color"
              data-vv-name="input.color"
              :error-messages="errors.collect('input.color')"
            ></v-text-field>

            <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
            <!-- <v-btn @click="show" :disabled="isDirty">Zobrazit ve zpěvníku</v-btn> -->
            <br>
            <br>
            <delete-model-dialog
              class-name="Songbook"
              :model-id="model.id"
              @deleted="is_deleted = true"
              delete-msg="Opravdu chcete vymazat tento zpěvník?"
            >Vymazat</delete-model-dialog>
          </v-form>
        </v-flex>
        <v-flex xs12 md6 class="edit-description">
          <h5>Seznam písní ve zpěvníku</h5>
          <v-radio-group v-if="model.songs_count" v-model="hide_empty">
            <v-radio
              label="Zobrazení přiřazených písní (vč. písní bez čísla)"
              :value="true"
            ></v-radio>
            <v-radio
              label="Zobrazení podle čísel"
              :value="false"
            ></v-radio>
          </v-radio-group>
          <v-data-table
            :headers="records_headers"
            :items="recordsWithEmpty"
            class="mb-4"
            :rows-per-page-items='[20,40,{"text":"Vše","value":-1}]'
          >
            <template v-slot:items="props">
              <td>{{ props.item.number }}</td>
              <td>
                <items-combo-box
                  v-bind:p-items="song_lyrics"
                  v-bind:value="props.item.song_lyric"
                  @input="(val) => { updateRecordItem(val, props.item.number) }"
                  header-label="Vyberte píseň"
                  label="Píseň"
                  :multiple="false"
                  :enable-custom="true"
                  create-label="Potvrďte enterem a vytvořte novou píseň"
                ></items-combo-box>
              </td>
              <td>
                <a class="as-link"
                  v-if="props.item.song_lyric && props.item.song_lyric.hasOwnProperty('id')"
                  @click.middle="goToAdminPage('song/' + props.item.song_lyric.id + '/edit', true, true)"
                  @click.left="goToAdminPage('song/' + props.item.song_lyric.id + '/edit')"
                >Upravit píseň</a>
              </td>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
      <!-- model deleted dialog -->
      <v-dialog v-model="is_deleted" persistent max-width="290">
        <v-card>
          <v-card-title class="headline">Zpěvník byl vymazán</v-card-title>
          <v-card-text>Zpěvník byl vymazán z databáze.</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="green darken-1"
              flat
              @click="goToAdminPage('songbook')"
            >Přejít na seznam zpěvníků</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </v-app>
</template>

<style lang="scss">
  a.as-link {
    color: #3f51b5 !important;

    &:hover {
      text-decoration: underline !important;
    }
  }
</style>

<script>
import gql from "graphql-tag";
import ItemsComboBox from "Admin/components/ItemsComboBox.vue";
import DeleteModelDialog from "Admin/components/DeleteModelDialog.vue";
import NumberInput from "Admin/components/NumberInput.vue";

import EditForm from "./EditForm";
import Songbook from "Admin/models/Songbook"

const FETCH_SONG_LYRICS = gql`
  query {
    song_lyrics {
      id
      name
    }
  }
`;

export default {
  components: {
    ItemsComboBox,
    DeleteModelDialog,
    NumberInput
  },
  extends: EditForm,

  data() {
    return {
      model: {
        // here goes the definition of model attributes
        // should match the definition in its ModelFillableFragment in (see graphql/client/model_fragment.graphwl)
        id: undefined,
        name: undefined,
        shortcut: undefined,
        records: [],
        songs_count: undefined,
        is_private: undefined,
        color: undefined
      },
      is_deleted: false,
      records_headers: [
        { text: "Číslo", value: "number" },
        { text: "Píseň", value: "name" },
        { text: "Akce", value: "action" }
      ],
      hide_empty: false,
      fragment: Songbook.fragment
    };
  },

  apollo: {
    model_database: {
      query: Songbook.QUERY,
      variables() {
        return Songbook.getQueryVariables(this.model);
      },
      result(result) {
        this.loadModelDataFromResult(result);
      }
    },
    song_lyrics: {
      query: FETCH_SONG_LYRICS
    }
  },

  mounted() {
    // send blocking info 
    setInterval(() => {
        // $.get( "/refresh-updating/songbook/" + this.presetId );
    }, 20000);
  },

  computed: {
    recordsWithEmpty() {
      if (this.hide_empty || !this.model.songs_count) {
        return this.model.records.filter(r => r.song_lyric !== null);
      }

      let result = [];

      for (var i = 1; i <= this.model.songs_count; i++) {
        let record = this.model.records.filter(r => r.number == i)[0];

        // if in the db there is already song under this number, then push that one
        // otherwise get an empty one

        if (record === undefined) {
          result.push({
            number: String(i),
            song_lyric: null
          });
        } else {
          result.push(record);
        }
      }

      return result;
    }
  },

  methods: {
    submit() {
      this.$apollo
        .mutate({
          mutation: Songbook.MUTATION,
          variables: Songbook.getMutationVariables(this.model)
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

          this.handleValidationErrors(error);
        });
    },

    show() {
      var base_url = document.querySelector("#baseUrl").getAttribute("value");
      window.location.href = base_url + "/autor/" + this.model.id;
    },

    updateRecordItem(song_lyric, number) {
      let record = this.model.records.filter(r => r.number == number)[0];

      if (record === undefined) {
        this.model.records.push({
          number: number,
          song_lyric: song_lyric
        });
      } else {
        this.$set(record, "song_lyric", song_lyric);
      }
    }
  }
};
</script>