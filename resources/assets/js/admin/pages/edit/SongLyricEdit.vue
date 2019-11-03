<template>
  <v-app>
    <notifications/>
    <!-- <v-fade-transition> -->
    <v-container fluid grid-list-xs v-show="!$apollo.loading">
      <v-tabs color="transparent" v-on:change="onTabChange">
        <v-tab>Údaje o písni</v-tab>
        <v-tab>Text</v-tab>
        <v-tab>Materiály</v-tab>
        <v-tab>Zpěvníky</v-tab>
        <v-tab-item>
          <v-layout row wrap pt-2>
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

                <v-radio-group v-model="model.only_regenschori" class="pt-0 mt-0">
                  <v-radio
                    label="Píseň určená pro Zpevnik.proscholy.cz + Regenschori.cz"
                    :value="false"
                  ></v-radio>
                  <v-radio
                    label="Píseň pouze pro Regenschori.cz"
                    :value="true"
                  ></v-radio>
                </v-radio-group>

                <v-layout row wrap>
                  <v-flex xs12 lg8>
                    <items-combo-box
                      v-bind:p-items="authors"
                      v-model="model.authors"
                      label="Autoři"
                      header-label="Vyberte autora z nabídky nebo vytvořte nového"
                      create-label="Potvrďte enterem a vytvořte nového autora"
                      :multiple="true"
                      :enable-custom="true"
                    ></items-combo-box>

                  </v-flex>
                  <v-flex xs12 lg4>
                  <v-checkbox :disabled="model.authors.length > 0"
                  class="mt-0"
                  v-model="model.has_anonymous_author"
                  label="Anonymní autor (nezobrazovat v to-do)"
                ></v-checkbox>
                  </v-flex>
                </v-layout>
                

                <v-card v-if="model.song && model_database.song" class="mb-3">
                  <v-card-title>Skupina písní</v-card-title>

                  <v-card-text>
                  <song-lyrics-group v-model="model.song.song_lyrics" :edit-id="model.id"></song-lyrics-group>
                  </v-card-text>

                  <v-card-actions>
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
                  </v-card-actions>
                </v-card>

                <items-combo-box
                  v-bind:p-items="tags_unofficial"
                  v-model="model.tags_unofficial"
                  label="Štítky"
                  header-label="Vyberte štítek z nabídky nebo vytvořte nový"
                  create-label="Potvrďte enterem a vytvořte nový štítek"
                  :multiple="true"
                  :enable-custom="true"
                ></items-combo-box>
                <items-combo-box
                  v-bind:p-items="tags_official"
                  v-model="model.tags_official"
                  label="Liturgie"
                  header-label="Vyberte část liturgie z nabídky"
                  :multiple="true"
                ></items-combo-box>
                <v-select :items="liturgy_approval_status_values" v-model="model.liturgy_approval_status" label="Liturgické schválení"></v-select>
                <!-- <v-checkbox :disabled="model.tags_official.length == 0"
                  class="mt-0"
                  v-model="model.liturgy_approval_status"
                  label="Schváleno pro použití v liturgii"
                ></v-checkbox> -->
              </v-form>
            </v-flex>
            <v-flex xs12 md5 offset-md1 class="edit-description">
              <h5>Název (povinná položka)</h5>
              <p>
                Název písně ve zvoleném jazyce (anglická píseň tedy bude mít anglický název). Může obsahovat název interpreta v závorkách, pokud existuje
                více písní se stejným názvem.
                <br>Konvence u anglických názvů je psaní všech slov kromě předložek velkými písmeny.
              </p>

              <h5>Autoři</h5>
              <p>
                Začněte zadávat jméno autora (textu nebo hudby) a pokud se vám během psaní zobrazí vyskakovací nabídka s hledaným jménem,
                tak jej označte kliknutím nebo Enterem. Pokud se autor v nabídce nenachází, znamená to, že ještě nebyl přidán do databáze.
                <!-- @can('add authors')To ale ničemu nevadí, stačí správně napsat jméno (resp. více jmen), potvrdit Enterem
                  a autor (autoři) se po uložení písně automaticky vytvoří.
                @else Je potřeba požádat administrátory o vytvoření nového autora @endcan-->
                <br>V současné verzi zpěvníku pro jednoduchost zatím nerozlišujeme vztah autora k písni.
              </p>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item>
          <v-layout row wrap>
            <v-flex xs12 md6>
              <v-select :items="lang_values" v-model="model.lang" label="Jazyk"></v-select>
              <!-- <v-text-field
                label="Kapodastr"
                required
                type="number"
                append-outer-icon="add" @click:append-outer="model.capo = parseInt(model.capo,10) + 1" 
                prepend-icon="remove" @click:prepend="model.capo = parseInt(model.capo,10) - 1"
                v-model="model.capo"
                data-vv-name="input.capo"
                :error-messages="errors.collect('input.capo')"
              ></v-text-field> -->
              <a
                id="file_select"
                class="btn btn-primary"
                v-on:click="$refs.fileinput.click()"
              >Nahrát ze souboru OpenSong</a>
              <input type="file" class="d-none" ref="fileinput" v-on:change="handleOpensongFile">
              <v-textarea
                auto-grow
                outline
                name="input-7-4"
                label="Text"
                ref="textarea"
                v-model="model.lyrics"
              ></v-textarea>
              <number-input 
                label="Kapodastr"
                v-model="model.capo"
                vv-name="input.capo"
                :min-value="0"
                :max-value="11"
                >
              </number-input>
              <p>
                Text písně je možné zadávat i s akordy v tzv. formátu ChordPro. Tedy např.
                <b>[E], [C#m] nebo [Cism], [Fmaj7]</b> apod.
                <br>Akordy pište českými značkami: H dur:
                <b>[H]</b>, B dur:
                <b>[B]</b>, B moll:
                <b>[Bm]</b>
                <br>Akordy v pozdějších slokách nepište přímo - můžete je označovat zástupným znakem [%], nakopírují se automaticky z první sloky
                <br>Sloky označujte číslicí, tečkou a mezerou: 1. Text první sloky
                <br>Refrén velkým R, dvojtečkou a mezerou: R: Text refrénu (při opakování už nepsat znovu text)
                <br>Bridge velkým B, dvojtečkou a mezerou: B: Text bridge
                <br>Coda velkým C, dvojtečkou a mezerou: C: Text cody
              </p>
            </v-flex>
            <v-flex xs12 md6>
              <!-- externals and files view -->
              <!-- <p v-for="file in model.files" v-bind:key="file.id">{{ file.public_name }}</p> -->
              <template v-if="thumbnailables">
                <v-select
                  :items="thumbnailables"
                  item-value="url"
                  item-text="public_name"
                  label="Náhled not (volba souboru/externího odkazu)"
                  v-model="selected_thumbnail_url"
                ></v-select>

                <!-- <v-img v-bind:src="selected_thumbnail_url" class="grey lighten-2"></v-img> -->
                <iframe :src="selected_thumbnail_url" frameborder="0" width="100%" height="500"></iframe>
              </template>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item>
          <v-layout row wrap mb-4>
            <v-flex xs12 md6>
              <h5>Externí odkazy:</h5>
              <v-btn
                v-for="external in model.externals"
                v-bind:key="external.id"
                class="text-none"
                @click="goToAdminPage('external/' + external.id + '/edit')"
              >{{ external.public_name }}</v-btn>
              <br>
              <v-btn
                color="info"
                outline
                @click="goToAdminPage('external/new-for-song/' + model.id)"
              >Přidat nový externí odkaz</v-btn>
            </v-flex>
            <v-flex xs12 md6>
              <h5>Soubory:</h5>
              <v-btn
                v-for="file in model.files"
                v-bind:key="file.id"
                class="text-none"
                @click="goToAdminPage('file/' + file.id + '/edit')"
              >{{ file.public_name }}</v-btn>
              <br>
              <v-btn
                color="info"
                outline
                @click="goToAdminPage('file/new-for-song/' + model.id)"
              >Přidat nový soubor</v-btn>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item>
          <v-layout row wrap>
            <v-flex xs12>
              <h5>Přiřazené zpěvníky:</h5>
            </v-flex>
          </v-layout>

          <v-layout row wrap v-for="(record, i) in model.songbook_records || []" :key="i">
            <v-flex xs4>
              <v-select
                v-model="record.songbook"
                :items="[...songbooks].sort((one, two) => one.name.localeCompare(two.name))"
                item-text="name"
                return-object
                label="Název zpěvníku"
              ></v-select>
            </v-flex>
            <v-flex xs2>
              <v-text-field label="Číslo písně" required v-model="record.number"></v-text-field>
            </v-flex>
            <v-flex xs2>
              <!-- <v-text-field label="Číslo písně" required v-model="record.number"></v-text-field> -->
              <v-btn color="error" outline @click="removeSongbookRecord(i)">Odstranit</v-btn>
            </v-flex>
          </v-layout>

          <v-layout row wrap>
            <v-flex xs12 class="mb-5">
              <v-btn
                color="info"
                outline
                @click="addSongbookRecord()"
              >Přidat nový záznam ve zpěvníku</v-btn>
            </v-flex>
          </v-layout>
        </v-tab-item>
      </v-tabs>
      <v-btn @click="submit" :disabled="!isDirty" class="success">Uložit</v-btn>
      <v-btn @click="reset" :disabled="!isDirty">Vrátit změny do stavu posledního uložení</v-btn>
      <v-btn @click="show" :disabled="isDirty">Zobrazit ve zpěvníku</v-btn>
      <!-- <v-btn @click="destroy" class="error">Vymazat</v-btn> -->
      <br>
      <br>
      <delete-model-dialog
        class-name="SongLyric"
        :model-id="model.id"
        @deleted="is_deleted = true"
        delete-msg="Opravdu chcete vymazat tuto píseň?"
      >Vymazat</delete-model-dialog>
      <!-- model deleted dialog -->
      <v-dialog v-model="is_deleted" persistent max-width="290">
        <v-card>
          <v-card-title class="headline">Píseň byla vymazána</v-card-title>
          <v-card-text>
            Pokud se to náhodou stalo omylem, tak není třeba zoufat, píseň máme pouze v koši, takže je možné ji obnovit.
            <br>
            Stačí se obrátit na administrátory s identifikací písně ID {{ model.id }} popř. názvem ({{model.name}})
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="green darken-1"
              flat
              @click="goToAdminPage('songs')"
            >Přejít na seznam písní</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>

    <v-container fluid v-show="$apollo.loading"><v-progress-circular
      indeterminate
    ></v-progress-circular></v-container>
    <!-- </v-fade-transition> -->
  </v-app>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";
import fragment from "Fragments/song_lyric_fragment.graphql";
import ItemsComboBox from "Admin/components/ItemsComboBox.vue";
import SongLyricsGroup from "Admin/components/SongLyricsGroup.vue";
import SelectSongGroupDialog from "Admin/components/SelectSongGroupDialog.vue";
import DeleteModelDialog from "Admin/components/DeleteModelDialog.vue";
import NumberInput from "Admin/components/NumberInput.vue";

const FETCH_MODEL_DATABASE = gql`
  query($id: ID!) {
    model_database: song_lyric(id: $id) {
      ...SongLyricFillableFragment
      lang_string_values
      liturgy_approval_status_string_values
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

const FETCH_SONGBOOKS = gql`
  query {
    songbooks {
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
    SelectSongGroupDialog,
    DeleteModelDialog,
    NumberInput
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
        only_regenschori: undefined,
        tags_unofficial: [],
        tags_official: [],
        authors: [],
        externals: [],
        files: [],
        songbook_records: [],
        song: undefined,
        capo: undefined,
        liturgy_approval_status: undefined
      },
      lang_values: [],
      liturgy_approval_status_values: [],
      selected_thumbnail_url: undefined,
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
        let song_lyric = result.data.model_database;
        // load the requested fields to the vue data.model property
        for (let field of this.getFieldsFromFragment(false)) {
          let clone = _.cloneDeep(song_lyric[field]);
          Vue.set(this.model, field, clone);
        }

        // lang string values are an associative array passed as JSON object
        const p = JSON.parse(song_lyric.lang_string_values);
        for (const [key, value] of Object.entries(p)) {
          this.lang_values.push({ value: key, text: value });
        }

        const pp = JSON.parse(song_lyric.liturgy_approval_status_string_values);
        console.log(pp)
        for (const [key, value] of Object.entries(pp)) {
          this.liturgy_approval_status_values.push({ value: parseInt(key), text: value });
        }

        // if there are any thumbnailables, then select the first one
        if (this.thumbnailables.length) {
          this.selected_thumbnail_url = this.thumbnailables[0].url;
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
    },
    songbooks: {
      query: FETCH_SONGBOOKS
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

    // send blocking info 
    setInterval(() => {
        $.get( "/refresh-updating/song-lyric/" + this.presetId );
    }, 1000);
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

    thumbnailables() {
      // mix the externals and files that can have thumbnail
      return this.model.externals
        .filter(ext => {
          return [0, 4, 8, 9].includes(ext.type);
        })
        .concat(
          this.model.files.filter(file => {
            return [1, 2, 3].includes(file.type);
          })
        );
    }
  },

  methods: {
    submit() {
      return this.$apollo
        .mutate({
          mutation: MUTATE_MODEL_DATABASE,
          variables: {
            input: {
              id: this.model.id,
              name: this.model.name,
              lang: this.model.lang,
              has_anonymous_author: this.model.has_anonymous_author,
              only_regenschori: this.model.only_regenschori,
              lyrics: this.model.lyrics,
              song: this.model.song,
              capo: this.model.capo,
              liturgy_approval_status: this.model.liturgy_approval_status,
              authors: {
                create: this.model.authors.filter(m => !m.hasOwnProperty("id")),
                sync: this.model.authors
                  .filter(m => m.hasOwnProperty("id"))
                  .map(m => m.id)
              },
              tags_unofficial: {
                create: this.model.tags_unofficial.filter(
                  m => !m.hasOwnProperty("id")
                ),
                sync: this.model.tags_unofficial
                  .filter(m => m.hasOwnProperty("id"))
                  .map(m => m.id)
              },
              tags_official: {
                // create: this.model.tags_official.filter(m => !m.hasOwnProperty("id")),
                sync: this.model.tags_official
                  .filter(m => m.hasOwnProperty("id"))
                  .map(m => m.id)
              },
              songbook_records: {
                // was not working
                // create: this.model.songbook_records.filter(m => typeof m.songbook === "string"),
                sync: this.model.songbook_records.map(m => ({
                  songbook_id: parseInt(m.songbook.id),
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

          this.$notify({
            title: "Chyba při ukládání",
            text:
              "Píseň nebyla uložena, opravte prosím chybějící pole označená červeně",
            type: "error"
          });
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

    onTabChange() {
      // it is needed to refresh the textareas manually
      if (this.$refs.textarea) {
        // somehow it doesn"t work without settimeout, not even with Vue.nexttick
        setTimeout(() => {
          this.$refs.textarea.calculateInputHeight();
        }, 1);
      }
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
    },

    addSongbookRecord() {
      this.model.songbook_records.push({
        number: "",
        songbook: {
          id: null,
          name: ""
        }
      });
    },

    removeSongbookRecord(i) {
      let name = this.model.songbook_records[i].songbook.name;
      if (name) {
        // not empty

        if (
          !confirm(
            "Opravdu chcete smazat záznam písničky ze zpěvníku " +
              name +
              "? (Změny se projeví až po uložení písničky)"
          )
        ) {
          return;
        }
      }

      this.$delete(this.model.songbook_records, i);
    }
  }
};
</script>
