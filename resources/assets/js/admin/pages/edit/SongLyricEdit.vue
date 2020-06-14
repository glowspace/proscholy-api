<template>
  <v-app>
    <notifications/>
    <!-- todo: position this loader to look good -->
    <!-- <v-container fluid v-show="$apollo.loading"><v-progress-circular
      indeterminate
    ></v-progress-circular></v-container> -->

    <!-- <v-fade-transition> -->
    <v-container fluid grid-list-xs>
      <v-tabs color="transparent" v-on:change="onTabChange">
        <v-tab>Údaje o písni</v-tab>
        <v-tab>Text</v-tab>
        <v-tab>Lilypond</v-tab>
        <v-tab>Materiály</v-tab>
        <v-tab>Zpěvníky</v-tab>
        <v-tab v-if="!is_arrangement_layout">Aranže</v-tab>
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

                <v-layout row mb-2>
                  <v-flex xs12 lg6>
                    <items-combo-box
                      v-if="is_arrangement_layout"
                      v-bind:p-items="song_lyrics.filter(sl => !sl.is_arrangement)"
                      v-model="model.arrangement_source"
                      label="Aranžovaná píseň"
                      header-label="Vyberte původní píseň pro tuto aranž"
                      create-label="Potvrďte enterem a vytvořte novou píseň"
                      :multiple="false"
                      :enable-custom="false"
                    ></items-combo-box>
                  </v-flex>
                  <v-flex xs12 lg6>
                    <v-btn v-if="is_arrangement_layout"
                          @click="goToAdminPage('song/' + model.arrangement_source.id + '/edit')"
                          :disabled="!model.arrangement_source"
                        >Přejít na editaci aranžované písně</v-btn>
                  </v-flex>
                </v-layout>

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
                  v-bind:p-items="tags_generic"
                  v-model="model.tags_generic"
                  label="Štítky (příležitosti)"
                  header-label="Vyberte štítek z nabídky nebo vytvořte nový"
                  create-label="Potvrďte enterem a vytvořte nový štítek"
                  :multiple="true"
                  :enable-custom="true"
                ></items-combo-box>
                <items-combo-box
                  v-bind:p-items="tags_saints"
                  v-model="model.tags_saints"
                  label="Štitky ke svatým"
                  header-label="Vyberte část liturgie z nabídky"
                  :multiple="true"
                ></items-combo-box>
                <items-combo-box
                  v-if="!is_arrangement_layout"
                  v-bind:p-items="tags_liturgy_part"
                  v-model="model.tags_liturgy_part"
                  label="Části liturgie"
                  header-label="Vyberte část liturgie z nabídky"
                  :multiple="true"
                  :disabled="model.liturgy_approval_status == 3"
                ></items-combo-box>
                <items-combo-box
                  v-if="!is_arrangement_layout"
                  v-bind:p-items="tags_liturgy_period"
                  v-model="model.tags_liturgy_period"
                  label="Liturgický rok"
                  header-label="Vyberte část liturgie z nabídky"
                  :multiple="true"
                ></items-combo-box>
                <items-combo-box
                  v-bind:p-items="tags_history_period"
                  v-model="model.tags_history_period"
                  label="Historické období (pro Regenschori)"
                  header-label="Vyberte štítek z nabídky nebo vytvořte nový"
                  create-label="Potvrďte enterem a vytvořte nový štítek"
                  :multiple="true"
                  :enable-custom="false"
                ></items-combo-box>
                <v-select :items="enums.missa_type" v-model="model.missa_type" label="Liturgický typ" v-if="is_arrangement_layout"></v-select>


                <v-select :items="enums.liturgy_approval_status" v-model="model.liturgy_approval_status" label="Liturgické schválení" v-if="!is_arrangement_layout"></v-select>

                <p class="mt-0" style="color:red" v-if="model.liturgy_approval_status == 3 && model.tags_liturgy_part.length > 0">
                  Stávající liturgické šítky budou po uložení odstraněny
                </p>
                <!-- <v-checkbox :disabled="model.tags_liturgy_part.length == 0"
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
              <v-select :items="enums.lang" v-model="model.lang" label="Jazyk" v-if="!is_arrangement_layout"></v-select>

              <!-- todo: re-enable when handleOpensongFile has been reimplemented to graphql -->
              <!-- <a
                id="file_select"
                class="btn btn-primary"
                v-on:click="$refs.fileinput.click()"
              >Nahrát ze souboru OpenSong</a>
              <input type="file" class="d-none" ref="fileinput" v-on:change="handleOpensongFile"> -->

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
                <ul>
                  <li>text písně je možné zadávat i s akordy v tzv. formátu ChordPro. Tedy např. <b>[E]</b>, <b>[C#m]</b> nebo <b>[Cism]</b>, <b>[Fmaj7]</b> apod.</li>
                  <li>akordy pište českými značkami – H dur: <b>[H]</b>, B dur: <b>[B]</b>, B moll: <b>[Bm]</b></li>
                  <li>akordy v pozdějších slokách nepište přímo - můžete je označovat zástupným znakem <b>[%]</b>, nakopírují se automaticky z první sloky</li>
                  <li>sloky označujte číslicí, tečkou a mezerou: <b>1. Text první sloky</b></li>
                  <li>refrén velkým R, dvojtečkou a mezerou – <b>R: Text refrénu</b> (při opakování už nepsat znovu text)</li>
                  <li>pokud je naprosto zřejmé, že na dané místo patří refrén (např. se opakuje po každé sloce písně), umisťuje se R: do závorky – <b>(R:)</b></li>
                  <li>R: nebo (R:) je nezbytné psát všude, kam v písni refrén patří (výrazně tak usnadníme práci hudebníkům)</li>
                  <li>bridge velkým B, dvojtečkou a mezerou – <b>B: Text bridge</b></li>
                  <li>coda velkým C, dvojtečkou a mezerou – <b>C: Text cody</b></li>
                  <li>předehra se značí pomocí zavináče – <b>@předehra: [Dm][C][F][C][B]</b></li>
                  <li>podobně se značí mezihra – <b>@mezihra: [Dm][C][F][C][B]</b></li>
                </ul>
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
                <!-- <iframe :src="selected_thumbnail_url" frameborder="0" width="100%" height="500"></iframe> -->
              </template>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item>
          <v-layout row wrap>
            <v-flex xs12 md6>
              <v-textarea
                auto-grow
                outline
                name="input-7-4"
                label="Notový zápis ve formátu Lilypond"
                ref="textarea"
                v-model="model.lilypond"
                v-on:keydown.tab.prevent="preventTextareaTab($event)"
                style="font-family: monospace; tab-size: 2;"
              ></v-textarea>
            </v-flex>
            <v-flex xs12 md6>
                <div v-html="lilypond_parse.svg" style="max-height: 70vh; overflow: scroll;"></div>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item>
          <v-layout row wrap mb-4 v-if="model_database">
            <v-flex xs12 md6>
              <h5>Externí odkazy:</h5>
              <v-btn
                v-for="external in model_database.externals"
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
                v-for="file in model_database.files"
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
        <v-tab-item v-if="!is_arrangement_layout && model_database">
          <v-layout row wrap mb-4>
            <v-flex xs12>
              <h5>Přidružené aranže:</h5>

              <v-btn
                v-for="arrangement in [...model_database.arrangements, ...created_arrangements]"
                v-bind:key="arrangement.id"
                class="text-none"
                @click="goToAdminPage('song/' + arrangement.id + '/edit')"
              >{{ arrangement.name }} 
              <span v-if="arrangement.authors && arrangement.authors.length">&nbsp;(autoři: {{ arrangement.authors.map(a => a.name).join(', ') }})</span>
              </v-btn>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs4>
              <v-text-field label="Název nové aranže" v-model="new_arrangement_name"></v-text-field>
            </v-flex>
            <v-flex xs4>
              <v-btn
                color="info"
                outline
                @click="createNewArrangement()"
                :disabled="new_arrangement_name.length == 0"
              >Přidat novou aranž písně</v-btn>
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
    <!-- </v-fade-transition> -->
  </v-app>
</template>

<script>
import gql from "graphql-tag";
import ItemsComboBox from "Admin/components/ItemsComboBox.vue";
import SongLyricsGroup from "Admin/components/SongLyricsGroup.vue";
import SelectSongGroupDialog from "Admin/components/SelectSongGroupDialog.vue";
import DeleteModelDialog from "Admin/components/DeleteModelDialog.vue";
import NumberInput from "Admin/components/NumberInput.vue";

import EditForm from './EditForm';
import SongLyric from 'Admin/models/SongLyric'

const FETCH_DATA = gql`
  query {
    authors {
      id
      name
    }
    song_lyrics {
      id
      name
      is_arrangement
    }
    songbooks {
      id
      name
    }
    tags_generic: tags_enum(type: GENERIC) {
      id
      name
    }
    tags_liturgy_part: tags_enum(type: LITURGY_PART) {
      id
      name
    }
    tags_liturgy_period: tags_enum(type: LITURGY_PERIOD) {
      id
      name
    }
    tags_history_period: tags_enum(type: HISTORY_PERIOD) {
      id
      name
    }
    tags_saints: tags_enum(type: SAINTS) {
      id
      name
    }
  }
`;

const CREATE_ARRANGEMENT = gql`
  mutation ($input: CreateArrangementInput!){
    create_arrangement(input: $input) {
      id
      name
    }
  }
`;

const FETCH_LILYPOND = gql`
  query ($lilypond: String) {
    lilypond_parse (lilypond: $lilypond) {
      svg
    }
  }
`;
export default {
  props: ["csrf"],
  components: {
    ItemsComboBox,
    SongLyricsGroup,
    SelectSongGroupDialog,
    DeleteModelDialog,
    NumberInput
  },
  extends: EditForm,

  data() {
    return {
      model: {
        // here goes the definition of model attributes
        id: undefined,
        name: undefined,
        has_anonymous_author: undefined,
        lang: undefined,
        lyrics: undefined,
        only_regenschori: undefined,
        tags_generic: [],
        tags_liturgy_part: [],
        tags_liturgy_period: [],
        tags_history_period: [],
        tags_saints: [],
        authors: [],
        externals: [],
        files: [],
        songbook_records: [],
        song: undefined,
        capo: undefined,
        liturgy_approval_status: undefined,
        arrangement_source: undefined,
        missa_type: undefined,
        lilypond: undefined
      },

      selected_thumbnail_url: undefined,
      is_loading: true,
      is_deleted: false,
      fragment: SongLyric.fragment,

      new_arrangement_name: "",
      created_arrangements: [],

      enums: {
        lang: [],
        liturgy_approval_status: [],
        missa_type: []
      }
    };
  },

  apollo: {
    model_database: {
      query: SongLyric.QUERY,
      variables() {
        return SongLyric.getQueryVariables(this.model);
      },
      result(result) {
        this.loadModelDataFromResult(result);
        this.loadEnumJsonFromResult(result, "lang_string_values", this.enums.lang);
        this.loadEnumJsonFromResult(result, "liturgy_approval_status_string_values", this.enums.liturgy_approval_status);
        this.loadEnumJsonFromResult(result, "missa_type_string_values", this.enums.missa_type);

        // if there are any thumbnailables, then select the first one
        if (this.thumbnailables.length) {
          this.selected_thumbnail_url = this.thumbnailables[0].url;
        }

        this.is_loading = false;
      }
    },
    authors: {
      query: FETCH_DATA
    },
    tags_liturgy_part: {
      query: FETCH_DATA
    },
    tags_generic: {
      query: FETCH_DATA
    },
    tags_history_period: {
      query: FETCH_DATA
    },
    tags_liturgy_period: {
      query: FETCH_DATA
    },
    tags_saints: {
      query: FETCH_DATA
    },
    songbooks: {
      query: FETCH_DATA
    },
    song_lyrics: {
      query: FETCH_DATA
    },
    lilypond_parse: {
      query: FETCH_LILYPOND,
      debounce: 200,
      variables() {
        return { lilypond: this.model.lilypond }
      }
    }
  },
  mounted() {
    // send blocking info 
    setInterval(() => {
        // $.get( "/refresh-updating/song-lyric/" + this.presetId );
    }, 5000);
  },  

  computed: {
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
    },

    is_arrangement_layout() {
      if (this.model_database) {
        return this.model_database.is_arrangement;
      }

      return false;
    }
  },

  methods: {
    submit() {
      return this.$apollo
        .mutate({
          mutation: SongLyric.MUTATION,
          variables: SongLyric.getMutationVariables(this.model)
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

          this.handleValidationErrors(error);

          this.$notify({
            title: "Chyba při ukládání",
            text:
              "Píseň nebyla uložena, opravte prosím chybějící pole označená červeně",
            type: "error"
          });
        });
    },

    show() {
      window.location.href = this.model_database.public_url;
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

    isDirtyChecker() {
      for (let field of ["tags_generic", "tags_liturgy_part"]) {
        if (!_.isEqual(this.model[field], this.model_database[field])) {
          return true;
        }
      }
    },

    preventTextareaTab(event) {
      let text = this.model.lilypond,
              originalSelectionStart = event.target.selectionStart,
              textStart = text.slice(0, originalSelectionStart),
              textEnd =  text.slice(originalSelectionStart);

      this.model.lilypond = `${textStart}\t${textEnd}`
      event.target.value = this.model.lilypond // required to make the cursor stay in place.
      event.target.selectionEnd = event.target.selectionStart = originalSelectionStart + 1
    },

    // todo: rewrite from jquery to graphql

    // handleOpensongFile(e) {
    //   var file = e.target.files[0];

    //   var reader = new FileReader();
    //   reader.onload = e => {
    //     console.log("file loaded succesfully");

    //     $.post(
    //       "/api/parse/opensong",
    //       {
    //         file_contents: e.target.result,
    //         _token: this.csrf
    //       },
    //       data => {
    //         this.model.lyrics = data;
    //       }
    //     );
    //   };

    //   reader.readAsText(file);
    // },

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
    },

    createNewArrangement() {
      this.$apollo
        .mutate({
          mutation: CREATE_ARRANGEMENT,
          variables: {
            input: {
              name: this.new_arrangement_name,
              arrangement_of: this.model.id
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

          this.new_arrangement_name = "";
          this.created_arrangements.push(result.data.create_arrangement);
        })
        .catch(error => {
          if (
            error.graphQLErrors.length == 0 ||
            error.graphQLErrors[0].extensions.validation === undefined
          ) {
            // unknown error happened
            this.$notify({
              title: "Chyba při vytváření aranže",
              text: "Aranž nebyla vytvořena",
              type: "error"
            });
            return;
          }

          // this.$notify({
          //   title: "Chyba při ukládání",
          //   text:
          //     "Píseň nebyla uložena, opravte prosím chybějící pole označená červeně",
          //   type: "error"
          // });
        });
    }
  }
};
</script>
