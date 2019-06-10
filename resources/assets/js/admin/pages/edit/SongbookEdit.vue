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

            <v-text-field
              label="Počet písní"
              required
              v-model="model.songs_count"
              data-vv-name="input.songs_count"
              :error-messages="errors.collect('input.songs_count')"
            ></v-text-field>

            <p v-if="!model.songs_count">
              Aby bylo možné zde editovat všechny záznamy, je třeba zadat celkový počet písní.
              <br>Tento údaj zatím není třeba zadávat přesně.
            </p>

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
          <!-- <v-checkbox
            class="mt-0"
            v-model="hide_empty"
            label="Skrýt prázdné záznamy"
            v-if="model.songs_count"
          ></v-checkbox> -->
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
                <a
                  v-if="props.item.song_lyric && props.item.song_lyric.hasOwnProperty('id')"
                  href="#"
                  @click="goToAdminPage('song/' + props.item.song_lyric.id + '/edit')"
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

const FETCH_SONG_LYRICS = gql`
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
        records: [],
        songs_count: undefined
      },
      is_deleted: false,
      records_headers: [
        { text: "Číslo", value: "number" },
        { text: "Píseň", value: "name" },
        { text: "Akce", value: "action" }
      ],
      hide_empty: false
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
        // Vue.set(this.model, "records", this.getRecordsWithEmpty(songbook["records"], songbook["songs_count"]));

        for (let field of this.getFieldsFromFragment(false)) {
          Vue.set(this.model, field, _.cloneDeep(songbook[field]));
        }
      }
    },
    song_lyrics: {
      query: FETCH_SONG_LYRICS
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
    },

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
          mutation: MUTATE_MODEL_DATABASE,
          variables: {
            input: {
              id: this.model.id,
              name: this.model.name,
              shortcut: this.model.shortcut,
              songs_count: this.model.songs_count,
              records: {
                // first let's filter out records that had been assigned a song_lyric but
                // it was then set to null
                sync: this.model.records
                  .filter(r => r.song_lyric !== null && r.song_lyric.hasOwnProperty("id"))
                  .map(m => ({
                    song_lyric_id: parseInt(m.song_lyric.id), 
                    number: m.number
                  })),
                create: this.model.records
                  .filter(r => r.song_lyric !== null && !r.song_lyric.hasOwnProperty("id"))
                  .map(m => ({
                    song_lyric_name: m.song_lyric.name,
                    number: m.number
                  }))
              }
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

    // helper method to load field names defined in fragment graphql definition
    getFieldsFromFragment(includeId, excludeFields = []) {
      let fieldDefs = fragment.definitions[0].selectionSet.selections;
      let fieldNames = fieldDefs.map(field => {
        if (field.alias) return field.alias.value;
        return field.name.value;
      });

      if (!includeId) fieldNames = fieldNames.filter(field => field != "id");

      fieldNames = fieldNames.filter(field => !excludeFields.includes(field));

      return fieldNames;
    },

    async goToPage(url, save = true) {
      if (this.isDirty && save) await this.submit();

      setTimeout(() => {
        if (!this.isDirty && save) {
          var base_url = document
            .querySelector("#baseUrl")
            .getAttribute("value");
          window.location.href = base_url + "/" + url;
        }
      }, 500);
    },

    goToAdminPage(url, save = true) {
      this.goToPage("/admin/" + url, save);
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
