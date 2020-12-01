<template>
  <v-app :dark="$root.dark">
    <notifications/>
    <div v-show="$apollo.loading" class="fixed-top"><v-progress-linear
      indeterminate
      color="info"
      :height="4"
      class="m-0"
    ></v-progress-linear></div>

    <!-- <v-fade-transition> -->
    <v-container fluid grid-list-xs>
        <div class="content-header">
      <h1 v-if="is_arrangement_layout">Úprava aranže</h1>
      <h1 v-else>Úprava písně</h1>
        </div>

      <v-layout row wrap>
        <v-flex grow>
          <v-textarea
            label="Prostor pro interní poznámku"
            v-model="model.admin_note"
            rows="1"
            auto-grow="1"
            :style="`opacity: ${model.admin_note ? 1 : 0.7}`"
          ></v-textarea>
        </v-flex>
        <v-flex xs12 sm2>
            <v-checkbox
            v-model="model.is_sealed"
            label="Zapečetit píseň"
          ></v-checkbox>
        </v-flex>
      </v-layout>

      <v-tabs color="transparent" v-model="active">
        <v-tab>Údaje o písni</v-tab>
        <v-tab>Text</v-tab>
        <v-tab>Materiály</v-tab>
        <v-tab>Zpěvníky</v-tab>
        <v-tab>Biblické reference</v-tab>
        <v-tab>Lilypond (beta)</v-tab>
        <v-tab v-if="!is_arrangement_layout && model_database">Aranže</v-tab>
        <v-tab-item :class="{'sealed' : model.is_sealed}">
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

                <span>pokud má píseň alternativní názvy nebo označení (např. Hymna CSM Ždár 2012), zadejte je zde:</span>
                <v-layout row wrap>
                  <v-flex xs12 md6>
                    <v-text-field
                      label="2. název písně"
                      v-model="model.secondary_name_1"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 md6>
                    <v-text-field
                      label="3. název písně"
                      v-model="model.secondary_name_2"
                    ></v-text-field>
                  </v-flex>
                </v-layout>

                <v-radio-group v-model="model.only_regenschori" class="pt-0 mt-0 mb-3" :hide-details="true">
                  <v-radio
                    label="Píseň určená pro Zpevnik.proscholy.cz + Regenschori.cz"
                    :value="false"
                  ></v-radio>
                  <v-radio
                    label="Skladba pouze pro Regenschori.cz"
                    :value="true"
                  ></v-radio>
                </v-radio-group>

                <v-select :items="enums.licence_type_cc" v-model="model.licence_type_cc" label="Licence"></v-select>

                <v-layout row mb-2 v-if="is_arrangement_layout">
                  <v-flex xs12 lg6>
                    <items-combo-box
                      v-bind:p-items="song_lyrics.filter(sl => !sl.is_arrangement)"
                      v-model="model.arrangement_source"
                      disabled
                      label="Aranžovaná píseň"
                      header-label="Vyberte původní píseň pro tuto aranž"
                      create-label="Potvrďte enterem a vytvořte novou píseň"
                      :multiple="false"
                      :enable-custom="false"
                    ></items-combo-box>
                  </v-flex>
                  <v-flex xs12 lg6>
                    <v-btn
                          @click="goToAdminPage('song/' + model.arrangement_source.id + '/edit')"
                          :disabled="!model.arrangement_source"
                          color="info" outline
                        >Přejít na editaci aranžované písně</v-btn>
                  </v-flex>
                </v-layout>

                <v-card class="mb-4 px-4">
                  <v-card-title class="p-0">
                    <h3>Autoři<span v-if="is_arrangement_layout"> aranže</span>
                    <span v-if="!is_original"> překladu</span>
                    </h3>
                  </v-card-title>

                  <v-card-text class="p-0">
                    <v-layout row wrap v-for="(author_pivot, i) in model.authors_pivot || []" :key="i">
                      <v-flex xs12 sm8>
                        <items-combo-box
                          v-model="author_pivot.author"
                          v-bind:p-items="authors"
                          item-text="name"
                          label="Jméno autora"
                          :multiple="false"
                          :enable-custom="true"
                          :disabled="model.has_anonymous_author"
                        ></items-combo-box>
                      </v-flex>
                      <v-flex xs10 sm3>
                        <!-- <v-text-field label="Číslo písně" required v-model="record.number"></v-text-field> -->
                        <v-select v-if="!is_arrangement_layout" :items="enums.authorship_type" v-model="author_pivot.authorship_type" label="Typ autora" :disabled="model.has_anonymous_author"></v-select>
                        <v-select v-else :items="[{text: 'Aranžér', value:'GENERIC'}]" v-model="author_pivot.authorship_type" label="Typ autora" :disabled="model.has_anonymous_author"></v-select>
                      </v-flex>
                      <v-flex xs2 sm1>
                        <!-- <v-text-field label="Číslo písně" required v-model="record.number"></v-text-field> -->
                        <v-btn icon @click="removeAuthor(i)" :disabled="model.has_anonymous_author" class="text-secondary"><i class="fas fa-trash"></i></v-btn>
                      </v-flex>
                    </v-layout>
                  </v-card-text>

                  <v-card-text class="p-0" v-if="model.authors_pivot.filter((el) => el.author != null).length === 0">
                    <span v-if="!model.has_anonymous_author">
                      Zatím k písni nikdo nepřiřadil autora (u písně je označení „autor<span v-if="!is_original"> překladu </span> neznámý“).
                    </span>
                    <span v-else>U písně je označení „anonymní autor“.</span>
                  </v-card-text>

                  <v-card-actions class="p-0">
                      <!-- <v-flex shrink mr-1>
                        <v-btn
                          :disabled="model.has_anonymous_author"
                          color="info"
                          outline
                          @click="addEmptyAuthor()"
                        >Přidat autora</v-btn>
                      </v-flex> -->
                      <v-flex grow mb-3>
                         <v-checkbox
                          :disabled="model.authors_pivot.filter((el) => el.author != null).length > 0"
                          class="mt-1"
                          v-model="model.has_anonymous_author"
                          label="Píseň má anonymního autora"
                          :hide-details="true"
                        ></v-checkbox>
                      </v-flex>
                  </v-card-actions>
                </v-card>

                <!-- <h3>Autoři</h3>

                <v-layout row wrap>
                  <v-flex xs12 class="mb-5">
                    <v-btn
                      color="info"
                      outline
                      @click="addEmptyAuthor()"
                    >Přidat autora</v-btn>
                  </v-flex>
                </v-layout> -->

                <v-card v-if="model.song && model_database.song" class="mb-3 px-4">
                  <v-card-title class="p-0"><h3>Skupina písní</h3></v-card-title>

                  <v-card-text class="py-0 px-2">
                    <song-lyrics-group v-model="model.song.song_lyrics" :edit-id="model.id"></song-lyrics-group>
                  </v-card-text>

                  <v-card-actions class="px-0 py-3">
                    <v-btn
                      color="error"
                      outline
                      @click="resetGroup"
                      v-if="model.song.song_lyrics.length > 1"
                    >Odstranit píseň ze skupiny</v-btn>
                    <select-song-group-dialog
                      outline
                      v-if="model_database.song.song_lyrics.length == 1 && model.song.song_lyrics.length == 1"
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
                  :disabled="model.liturgy_approval_status == 1"
                ></items-combo-box>
                <p class="mt-0" v-if="model.liturgy_approval_status == 1">
                  <i>lit. štítky nelze upravovat, když je písnička označená jako schválená ČBK pro liturgii (viz níže)</i>
                </p>
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
                <items-combo-box
                  v-bind:p-items="tags_musical_form"
                  v-model="model.tags_musical_form"
                  label="Hudební forma"
                  header-label="Vyberte odpovídající hudební (liturgické) formy"
                  :multiple="true"
                  :enable-custom="false"
                ></items-combo-box>

                <v-select :items="enums.liturgy_approval_status" v-model="model.liturgy_approval_status" label="Liturgické schválení" v-if="!is_arrangement_layout"></v-select>
              </v-form>
            </v-flex>
            <v-flex xs12 md6 class="edit-description pl-md-4">
              <h5>Název (povinná položka)</h5>
              <p>
                Zadejte název písně ve zvoleném jazyce (anglická píseň tedy bude mít anglický název).
                <br>Může obsahovat název interpreta v závorkách, pokud existuje více písní se stejným názvem.
                <br>Konvence u anglických názvů je psaní všech slov kromě předložek velkými písmeny.
              </p>

              <h5 class="mt-4">Autoři</h5>
              <p>
                Po kliknutí do pole Jméno autora začněte zadávat autorovo jméno. Pokud se nenachází ve vyskakovací nabídce,
                stačí napsat jeho celé jméno a stisknout Enter. Tak se přidá nový autor (zeleně označený).
                Změna v databázi (tzn. samotné vytvoření autora) se provede až po uložení celé písně.
              </p>
              <h5 class="mt-4">Licence</h5>
              <p>
                Typ licence podle vzoru <a href="https://www.creativecommons.cz/licence-cc/varianty-licence/">Creative Commons</a>, případně proprietární smlouva.
                Pole s licencí vyplňte, pokud máte k dispozici písemný souhlas s konkrétní podobou otevřené licence, popř. podepsanou smlouvu s Musica Sacra.
              </p>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item :class="{'sealed' : model.is_sealed}">
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
                class="auto-grow-alt"
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
                  <li>akordy v pozdějších slokách nepište přímo – můžete je označovat zástupným znakem <b>[%]</b>, nakopírují se automaticky z první sloky</li>
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
              <!-- externals view -->
              <template v-if="thumbnailables">
                <v-select
                  :items="thumbnailables"
                  return-object
                  item-text="url"
                  label="Náhled not (volba materiálu)"
                  v-model="selected_thumbnail_external"
                ></v-select>

                <external-component
                  v-if="selected_thumbnail_external"
                  :external="selected_thumbnail_external"
                  height="55vh"
                ></external-component>

                <!-- <v-img v-bind:src="selected_thumbnail_url" class="grey lighten-2"></v-img> -->
                <!-- <iframe :src="selected_thumbnail_url" frameborder="0" width="100%" height="500"></iframe> -->
              </template>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item :class="{'sealed' : model.is_sealed}">
          <v-layout row wrap mb-4 v-if="model_database">
            <v-flex xs12>
              <CreateExternal :song-lyric-id="Number(model.id)" v-on:create="onExternalCreated"/>

              <h4 v-if="externals_recordings.length">Nahrávky</h4>
              <ExternalListItem v-for="ext in externals_recordings" :key="ext.id" :external="ext" @delete="onExternalDeleted" @update="id => goToAdminPage('external/' + id + '/edit')"/>
              <h4 v-if="externals_scores.length">Noty</h4>
              <ExternalListItem v-for="ext in externals_scores" :key="ext.id" :external="ext" @delete="onExternalDeleted" @update="id => goToAdminPage('external/' + id + '/edit')"/>
              <h4 v-if="externals_others.length">Ostatní materiály</h4>
              <ExternalListItem v-for="ext in externals_others" :key="ext.id" :external="ext" @delete="onExternalDeleted" @update="id => goToAdminPage('external/' + id + '/edit')"/>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item :class="{'sealed' : model.is_sealed}">
          <v-layout row wrap>
            <v-flex xs12>
              <h4 v-if="(model.songbook_records || []).length">Přiřazené zpěvníky</h4>
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
        <v-tab-item :class="{'sealed' : model.is_sealed}">
          <v-layout row wrap class="pt-2">
            <v-flex xs12 md6 class="pr-2">
              <v-textarea
                class="auto-grow-alt"
                outline
                name="input-bible"
                label="Biblické reference (v běžném formátu)"
                ref="textarea-bible"
                v-model="model.bible_refs_src"
              ></v-textarea>
            </v-flex>
            <v-flex xs12 md6>
              <h4 class="mb-0">Strojově interpretované reference:</h4>
              <p>- jednotný formát, slouží pro ověření správného zadání referencí<br/>
                - klikem na odkaz se otevře bibleserver.com s daným úryvkem</p>
              <div style="font-size: 1.3em">
                <span v-for="(reference, i) in bible_refs_czech" :key="i">
                  <a :href="`https://www.bibleserver.com/CEP/${reference}`" target="_blank">{{ reference }}</a><br/>
                </span>
              </div>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item :class="{'sealed' : model.is_sealed}">
          <v-layout row wrap class="pt-2">
            <v-flex xs12 md6>
              <v-textarea
                class="auto-grow-alt"
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
                <div v-if="lilypond_parse" v-html="lilypond_parse.svg" v-show="model.lilypond" style="max-height: 70vh; overflow: scroll;"></div>
                <div v-else>Náhled lilypondu není dostupný</div>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item v-if="!is_arrangement_layout && model_database">
          <v-layout row wrap mb-4>
            <v-flex xs12>
              <h4 v-if="[...model_database.arrangements, ...created_arrangements].length">Přidružené aranže</h4>

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
      <div class="position-sticky fixed-bottom body-bg ml-n3 mb-0 p-2 card d-inline-block overflow-auto" style="max-height:15vh;z-index:2">
        <v-tooltip top>
            <template v-slot:activator="{ on }">
                <v-btn @click="submit" :disabled="!isDirty" class="success" v-on="on"><i class="fas fa-save mr-2"></i> Uložit</v-btn>
            </template>
            <span>Ctrl + S</span>
        </v-tooltip>
        <v-btn @click="reset" :disabled="!isDirty"><i class="fas fa-undo mr-2"></i> Vrátit změny do stavu posledního uložení</v-btn>
        <v-btn v-if="model_database && model_database.public_url" :href="model_database.public_url" class="text-decoration-none mr-0" :disabled="isDirty"><i class="far fa-eye mr-2"></i> Zobrazit ve zpěvníku</v-btn>
        <v-btn v-if="model_database && model_database.public_url" :href="model_database.public_url" class="text-decoration-none ml-0" target="_blank" icon><i class="fas fa-external-link-alt"></i></v-btn>
      </div>
      <div class="mt-2 mb-4 ml-n3 p-2">
        <!-- <v-btn @click="destroy" class="error">Vymazat</v-btn> -->
        <delete-model-dialog
          class-name="SongLyric"
          :model-id="model.id"
          @deleted="is_deleted = true"
          delete-msg="Opravdu chcete vymazat tuto píseň?"
        ><i class="fas fa-trash mr-2"></i> Vymazat</delete-model-dialog>
      </div>
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

<style>
  .sealed {
    opacity: 0.7;
    pointer-events: none;
  }
</style>

<script>
import gql from "graphql-tag";
import ItemsComboBox from "Admin/components/ItemsComboBox.vue";
import SongLyricsGroup from "Admin/components/SongLyricsGroup.vue";
import SelectSongGroupDialog from "Admin/components/SelectSongGroupDialog.vue";
import DeleteModelDialog from "Admin/components/DeleteModelDialog.vue";
import NumberInput from "Admin/components/NumberInput.vue";
import CreateExternal from "Admin/components/CreateExternal.vue";
import ExternalListItem from "Admin/components/ExternalListItem.vue";
import ExternalComponent from '@bit/proscholy.utilities.external/External.vue';

import EditForm from './EditForm';
import SongLyric from 'Admin/models/SongLyric';
import { graphqlErrorsToValidator } from 'Admin/helpers/graphValidation';


// import { bcv_parser } from "bible-passage-reference-parser/js/cs_bcv_parser";
import BibleReference from "bible-reference/bible_reference";

const FETCH_DATA = gql`
  query {
    authors(order_last_associated: true) {
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
    tags_musical_form: tags_enum(type: MUSICAL_FORM) {
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
  components: {
    ItemsComboBox,
    SongLyricsGroup,
    SelectSongGroupDialog,
    DeleteModelDialog,
    NumberInput,
    CreateExternal,
    ExternalListItem,
    ExternalComponent
  },
  extends: EditForm,

  data() {
    return {
      model: {
        // here goes the definition of model attributes
        id: undefined,
        name: undefined,
        secondary_name_1: undefined,
        secondary_name_2: undefined,
        licence_type_cc: undefined,
        has_anonymous_author: undefined,
        lang: undefined,
        lyrics: undefined,
        only_regenschori: undefined,
        tags_generic: [],
        tags_liturgy_part: [],
        tags_liturgy_period: [],
        tags_history_period: [],
        tags_saints: [],
        tags_musical_form: [],
        authors_pivot: [],
        externals: [],
        songbook_records: [],
        song: undefined,
        capo: undefined,
        liturgy_approval_status: undefined,
        arrangement_source: undefined,
        lilypond: "",
        bible_refs_src: "",
        bible_refs_osis: "",
        admin_note: undefined
      },

      selected_thumbnail_external: undefined,
      is_loading: true,
      is_deleted: false,
      fragment: SongLyric.fragment,
      // only for displaying parsed references
      bible_refs_czech: [],

      new_arrangement_name: "",
      created_arrangements: [],
      created_externals: [],

      enums: {
        lang: [],
        liturgy_approval_status: [],
        authorship_type: [],
        licence_type_cc: []
      },

      active: 0
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
        this.loadEnumJsonFromResult(result, "authorship_type_string_values", this.enums.authorship_type);
        this.loadEnumJsonFromResult(result, "licence_type_cc_string_values", this.enums.licence_type_cc);

        // if there are any thumbnailables, then select the first one
        if (this.thumbnailables.length) {
          this.selected_thumbnail_external = this.thumbnailables[0];
        }

        this.is_loading = false;
        // load lilypond
        // this.debounceLilypondUrl()
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
    tags_musical_form: {
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
    if (window.location.hash.length) {
      this.active = window.location.hash.replace('#', '') - 0;
    }
  },

  computed: {
    thumbnailables() {
      if (!this.model_database) {
        return [];
      }

      const hasSupportedContent = content_type => ['SCORE', 'LYRICS', 'WEBSITE'].includes(content_type);
      const hasSupportedFileFormat = media_type => !['file/musx'].includes(media_type);

      // externals that can have thumbnail
      return [...this.model_database.externals, ...this.created_externals]
        .filter(ext => hasSupportedContent(ext.content_type) && hasSupportedFileFormat(ext.media_type));
    },

    is_arrangement_layout() {
      if (this.model_database) {
        return this.model_database.is_arrangement;
      }

      return false;
    },

    is_original() {
      if (this.model && this.model.song) {
        const song_lyric_type = this.model.song.song_lyrics.find(sl =>
          sl.id == this.model.id
        ).type;

        return song_lyric_type == 0;
      }

      return true;
    },

    authors_pivot_comp() {
      return this.model.authors_pivot;
    },

    csrf() {
      return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },

    externals_others() {
      return [...this.model_database.externals, ...this.created_externals].filter(ext => ['UNDEFINED', 'WEBSITE', 'LYRICS'].includes(ext.content_type));
    },
    externals_recordings() {
      return [...this.model_database.externals, ...this.created_externals].filter(ext => ['RECORDING'].includes(ext.content_type));
    },
    externals_scores(){
      return [...this.model_database.externals, ...this.created_externals].filter(ext => ['SCORE'].includes(ext.content_type));
    },
  },

  watch: {
    authors_pivot_comp: {
      handler(val) {
        if (!val.length || val[val.length-1].author !== null) {
          this.addEmptyAuthor();
        }
      },
      deep: true
    },

    "model.bible_refs_src": function() {
      if (this.model.bible_refs_src) {
        const lines = this.model.bible_refs_src.split("\n");
        const bib_refs = lines.map(l => BibleReference.fromEuropean(l));
        const lines_osis = bib_refs.map(r => r.toString()).join(',');
        const lines_cz = bib_refs.flatMap(r => r.toCzechStrings());

        this.model.bible_refs_osis = lines_osis;
        this.bible_refs_czech = lines_cz;
      } else {
        this.model.bible_refs_osis = "";
        this.bible_refs_czech = [];
      }
    },

    active(val) {
      window.location.hash = val ? val : '';
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

          graphqlErrorsToValidator(this.$validator, error);

          this.$notify({
            title: "Chyba při ukládání",
            text:
              "Píseň nebyla uložena, opravte prosím chybějící pole označená červeně",
            type: "error"
          });
        });
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

      this.$validator.errors.clear();
    },

    onExternalCreated(external) {
      this.created_externals.push(external);
    },

    onExternalDeleted(id) {
      const indexOfExtId = (externals, id) => externals.map(ex => ex.id).indexOf(id);
      // External can be stored either in model_database or in created_externals,
      // we need to figure out where
      const i_mdb = indexOfExtId(this.model_database.externals, id);
      const i_created = indexOfExtId(this.created_externals, id);

      if (i_mdb) {
        Vue.delete(this.model_database.externals, i_mdb);
      } else if (i_created) {
        Vue.delete(this.created_externals, i_created);
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

    addEmptyAuthor() {
      this.model.authors_pivot.push({
        authorship_type: "GENERIC",
        author: null
      })
    },

    removeAuthor(i) {
      this.$delete(this.model.authors_pivot, i);
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
