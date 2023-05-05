<template>
    <v-app :dark="$root.dark">
        <notifications />
        <div v-show="$apollo.loading" class="fixed-top">
            <v-progress-linear
                indeterminate
                color="info"
                :height="4"
                class="m-0"
            ></v-progress-linear>
        </div>
        <v-container fluid grid-list-xs>
            <h1>Úprava zpěvníku</h1>
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
                            :min-value="0"
                        >
                        </number-input>

                        <p v-if="!model.songs_count">
                            Aby bylo možné zde editovat všechny záznamy, je
                            třeba zadat celkový počet písní.
                            <br />Tento údaj zatím není třeba zadávat přesně.
                        </p>

                        <v-text-field
                            label="Barva"
                            v-model="model.color"
                            data-vv-name="input.color"
                            :error-messages="errors.collect('input.color')"
                        ></v-text-field>

                        <v-text-field
                            label="Barva textu"
                            v-model="model.color_text"
                            data-vv-name="input.color_text"
                            :error-messages="errors.collect('input.color_text')"
                        ></v-text-field>

                        <div class="d-sm-table">
                            <v-text-field
                                label="URL adresa obrázku"
                                v-model="model.songbook_img_url"
                                class="d-table-cell w-100"
                            ></v-text-field>
                            <span class="pl-3 d-table-cell align-center"
                                >nebo</span
                            >
                            <FileUploadDialog
                                v-on:submit="onFileDialogSubmit"
                            ></FileUploadDialog>
                        </div>

                        <v-btn @click="submit" :disabled="!isDirty"
                            >Uložit</v-btn
                        >
                        <!-- <v-btn :disabled="isDirty">Zobrazit ve zpěvníku</v-btn> -->
                        <br />
                        <br />
                        <delete-model-dialog
                            class-name="Songbook"
                            :model-id="model.id"
                            @deleted="is_deleted = true"
                            delete-msg="Opravdu chcete vymazat tento zpěvník?"
                            >Vymazat</delete-model-dialog
                        >
                    </v-form>
                </v-flex>
                <v-flex xs12 md6 class="edit-description pl-md-4">
                    <h5>Seznam písní ve zpěvníku</h5>
                    <v-radio-group
                        v-if="model.songs_count"
                        v-model="hide_empty"
                    >
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
                        class="mb-4 card"
                        :rows-per-page-items="[
                            10,
                            50,
                            {
                                text: '$vuetify.dataIterator.rowsPerPageAll',
                                value: -1
                            }
                        ]"
                        :loading="$apollo.loading"
                        :no-data-text="
                            $apollo.loading ? 'Načítám…' : '$vuetify.noDataText'
                        "
                        :custom-sort="customSort"
                    >
                        <template v-slot:items="props">
                            <td>{{ props.item.number }}</td>
                            <td>
                                <items-combo-box
                                    v-bind:p-items="song_lyrics_augmented"
                                    v-bind:value="props.item.song_lyric"
                                    @input="
                                        val => {
                                            updateRecordItem(
                                                val,
                                                props.item.number
                                            );
                                        }
                                    "
                                    header-label="Vyberte píseň"
                                    label="Píseň"
                                    :multiple="false"
                                    :enable-custom="true"
                                    create-label="Potvrďte enterem a vytvořte novou píseň"
                                    :no-label="true"
                                    item-text="name_display"
                                    :filter="song_lyric_filter"
                                ></items-combo-box>
                            </td>
                            <td>
                                <a
                                    v-if="
                                        props.item.song_lyric &&
                                            props.item.song_lyric.hasOwnProperty(
                                                'id'
                                            )
                                    "
                                    class="text-secondary"
                                    :href="
                                        '/admin/song/' +
                                            props.item.song_lyric.id +
                                            '/edit'
                                    "
                                    ><i class="fas fa-pen"></i
                                ></a>
                            </td>
                        </template>
                    </v-data-table>
                </v-flex>
            </v-layout>
            <!-- model deleted dialog -->
            <v-dialog v-model="is_deleted" persistent max-width="290">
                <v-card>
                    <v-card-title class="headline"
                        >Zpěvník byl vymazán</v-card-title
                    >
                    <v-card-text>Zpěvník byl vymazán z databáze.</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green darken-1"
                            flat
                            @click="goToAdminPage('songbook')"
                            >Přejít na seznam zpěvníků</v-btn
                        >
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
import gql from 'graphql-tag';
import ItemsComboBox from 'Admin/components/ItemsComboBox.vue';
import DeleteModelDialog from 'Admin/components/DeleteModelDialog.vue';
import NumberInput from 'Admin/components/NumberInput.vue';
import FileUploadDialog from 'Admin/components/FileUploadDialog';
import Vue from 'vue';

import EditForm from './EditForm';
import Songbook from 'Admin/models/Songbook';
import { graphqlErrorsToValidator } from 'Admin/helpers/graphValidation';
import {
    getSongLyricFullName,
    stringToSearchable
} from '../../helpers/search_indexing';

const augmentedSongLyric = sl => ({
    ...sl,
    name_search: stringToSearchable(getSongLyricFullName(sl)),
    name_display: getSongLyricFullName(sl)
});

const FETCH_SONG_LYRICS = gql`
    query {
        song_lyrics {
            id
            name
            secondary_name_1
            secondary_name_2
        }
    }
`;

export default {
    components: {
        ItemsComboBox,
        DeleteModelDialog,
        NumberInput,
        FileUploadDialog
    },
    extends: EditForm,

    data() {
        return {
            model: {
                // here goes the definition of model attributes
                id: undefined,
                name: undefined,
                shortcut: undefined,
                records: [],
                songs_count: undefined,
                is_private: undefined,
                color: undefined,
                color_text: undefined,
                songbook_img_url: undefined
            },
            is_deleted: false,
            records_headers: [
                { text: 'Číslo', value: 'number' },
                { text: 'Píseň', value: 'name' },
                { text: 'Akce', value: 'actions', sortable: false }
            ],
            hide_empty: false,
            fragment: Songbook.fragment,
            song_lyrics_augmented: []
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

                // do the following,
                // so that the ItemComboBox can properly display the name
                // of the selected song_lyric

                // needs to be updated also on model_database because of dirty check
                for (let record of this.model_database.records) {
                    Vue.set(
                        record.song_lyric,
                        'name_display',
                        getSongLyricFullName(record.song_lyric)
                    );
                }

                for (let record of this.model.records) {
                    Vue.set(
                        record.song_lyric,
                        'name_display',
                        getSongLyricFullName(record.song_lyric)
                    );
                }
            }
        },
        song_lyrics: {
            query: FETCH_SONG_LYRICS,
            result() {
                this.song_lyrics_augmented = this.song_lyrics.map(
                    augmentedSongLyric
                );
            }
        }
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
                        title: 'Úspěšně uloženo :)',
                        text: 'Zpěvník byl úspěšně uložen',
                        type: 'success'
                    });
                })
                .catch(error => {
                    if (error.graphQLErrors.length == 0) {
                        // unknown error happened
                        this.$notify({
                            title: 'Chyba při ukládání',
                            text: 'Zpěvník nebyl uložen',
                            type: 'error'
                        });
                        return;
                    }

                    graphqlErrorsToValidator(this.$validator, error);
                });
        },

        updateRecordItem(song_lyric, number) {
            let record = this.model.records.filter(r => r.number == number)[0];

            if (record === undefined) {
                this.model.records.push({
                    number: number,
                    song_lyric: song_lyric
                });
            } else {
                this.$set(record, 'song_lyric', song_lyric);
            }
        },

        customSort(items, index, isDesc) {
            items.sort((a, b) => {
                if (index == 'number') {
                    if (!isDesc) {
                        return this.orderNumber(a, b);
                    } else {
                        return this.orderNumber(a, b) * -1;
                    }
                } else {
                    if (!a.song_lyric && !b.song_lyric) {
                        return 0;
                    } else if (!a.song_lyric) {
                        return 1;
                    } else if (!b.song_lyric) {
                        return -1;
                    } else if (!isDesc) {
                        return a.song_lyric.name
                            .toLowerCase()
                            .localeCompare(b.song_lyric.name.toLowerCase());
                    } else {
                        return b.song_lyric.name
                            .toLowerCase()
                            .localeCompare(a.song_lyric.name.toLowerCase());
                    }
                }
            });
            return items;
        },

        orderNumber(a, b) {
            let on = 0;

            if (!a.number) {
                on = -1;
            } else if (!b.number) {
                on = 1;
            } else {
                let aNumber = parseInt(a.number.replace(/\D+/g, ''));
                let bNumber = parseInt(b.number.replace(/\D+/g, ''));
                let aString = a.number.replace(/\d+/g, '');
                let bString = b.number.replace(/\d+/g, '');

                if (aNumber > bNumber) {
                    on = 1;
                } else if (bNumber > aNumber) {
                    on = -1;
                } else if (aString > bString) {
                    on = 1;
                } else if (bString > aString) {
                    on = -1;
                }
            }

            return on;
        },

        onFileDialogSubmit(url) {
            this.model.songbook_img_url = url;
        },

        song_lyric_filter(item, queryText) {
            if (item.header) return false;
            if (!item.name_search) return false; 

            return item.name_search.indexOf(stringToSearchable(queryText)) > -1;
        }
    }
};
</script>
