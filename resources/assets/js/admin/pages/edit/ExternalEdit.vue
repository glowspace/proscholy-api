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
            <h1 class="h2 mb-3">Úprava materiálu</h1>
            <v-layout row wrap>
                <v-flex xs12 md6>
                    <v-form ref="form">
                        <v-layout row>
                            <v-flex grow>
                                <v-text-field
                                    label="URL adresa"
                                    required
                                    v-model="model.url"
                                    data-vv-name="input.url"
                                    :error-messages="
                                        errors.collect('input.url')
                                    "
                                    :disabled="model.is_uploaded"
                                ></v-text-field>
                            </v-flex>
                            <v-flex shrink>
                                <FileUploadDialog
                                    v-on:submit="onFileDialogSubmit"
                                    :btn-caption="
                                        model.is_uploaded
                                            ? 'Nahradit jiným souborem'
                                            : 'Nahradit souborem'
                                    "
                                ></FileUploadDialog>
                            </v-flex>
                        </v-layout>
                        <v-text-field
                            label="Zobrazovaný název"
                            v-model="model.caption"
                        ></v-text-field>
                        <v-combobox
                            :items="enums.media_type.map(i => i.text)"
                            v-model="model.media_type"
                            label="Typ odkazu/souboru"
                        ></v-combobox>
                        <v-select
                            :items="enums.content_type"
                            v-model="model.content_type"
                            label="Typ obsahu"
                        ></v-select>
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

                        <!-- string values -->
                        <v-text-field
                            label="Editor"
                            v-model="model.editor"
                        ></v-text-field>
                        <v-text-field
                            label="Publikoval"
                            v-model="model.published_by"
                        ></v-text-field>
                        <v-text-field
                            label="Katalogové číslo"
                            v-model="model.catalog_number"
                        ></v-text-field>
                        <v-text-field
                            label="Copyright"
                            v-model="model.copyright"
                        ></v-text-field>
                    </v-form>
                </v-flex>
                <v-flex xs12 md6>
                    <external-component
                        v-if="model_database"
                        :external="model_database"
                    ></external-component>
                </v-flex>
            </v-layout>
            <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
            <v-btn
                v-if="model.song_lyric"
                :disabled="isDirty"
                @click="goToAdminPage('song/' + model.song_lyric.id + '/edit')"
                >Přejít na editaci písničky</v-btn
            >
            <v-btn
                v-if="model.song_lyric"
                :disabled="isDirty"
                @click="showSong()"
                >Zobrazit píseň ve zpěvníku</v-btn
            >
            <br />
            <br />
            <delete-model-dialog
                class-name="External"
                :model-id="model.id || null"
                @deleted="is_deleted = true"
                delete-msg="Opravdu chcete vymazat tento materiál?"
                >Vymazat</delete-model-dialog
            >
            <!-- model deleted dialog -->
            <v-dialog v-model="is_deleted" persistent max-width="320">
                <v-card>
                    <v-card-title class="headline"
                        >Materiál byl vymazán</v-card-title
                    >
                    <v-card-text>Materiál byl vymazán z databáze.</v-card-text>
                    <v-card-actions
                        class="d-flex flex-column justify-content-end"
                    >
                        <v-spacer></v-spacer>
                        <div>
                            <v-btn
                                color="green darken-1"
                                flat
                                @click="goToAdminPage('external', false)"
                                >Přejít na seznam materiálů</v-btn
                            >
                        </div>
                        <div>
                            <v-btn
                                v-if="model.song_lyric"
                                color="green darken-1"
                                flat
                                @click="
                                    goToAdminPage(
                                        'song/' + model.song_lyric.id + '/edit',
                                        false
                                    )
                                "
                                >Přejít na editaci písně</v-btn
                            >
                        </div>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-container>
    </v-app>
</template>

<script>
import gql from 'graphql-tag';
import ItemsComboBox from 'Admin/components/ItemsComboBox.vue';
import DeleteModelDialog from 'Admin/components/DeleteModelDialog.vue';
import ExternalComponent from '@bit/proscholy.utilities.external/External.vue';
import FileUploadDialog from 'Admin/components/FileUploadDialog.vue';

import EditForm from './EditForm';
import External from 'Admin/models/External';
import { graphqlErrorsToValidator } from 'Admin/helpers/graphValidation';

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
        ExternalComponent,
        FileUploadDialog
    },
    extends: EditForm,

    data() {
        return {
            model: {
                // here goes the definition of model attributes
                id: undefined,
                url: undefined,
                authors: [],
                song_lyric: undefined,
                tags_instrumentation: [],
                catalog_number: undefined,
                copyright: undefined,
                editor: undefined,
                published_by: undefined,
                is_uploaded: undefined,
                caption: undefined,
                media_type: undefined,
                content_type: undefined
            },
            enums: {
                media_type: [],
                content_type: []
            },
            fragment: External.fragment,
            is_deleted: false
        };
    },

    apollo: {
        model_database: {
            query: External.QUERY,
            variables() {
                return External.getQueryVariables(this.model);
            },

            result(result) {
                this.loadModelDataFromResult(result);
                this.loadEnumJsonFromResult(
                    result,
                    'media_type_values',
                    this.enums.media_type
                );
                this.loadEnumJsonFromResult(
                    result,
                    'content_type_string_values',
                    this.enums.content_type
                );
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
        }
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
                        title: 'Úspěšně uloženo :)',
                        text: 'Materiál byl úspěšně uložen',
                        type: 'success'
                    });
                })
                .catch(error => {
                    if (error.graphQLErrors.length == 0) {
                        // unknown error happened
                        this.$notify({
                            title: 'Chyba při ukládání',
                            text: 'Materiál nebyl uložen',
                            type: 'error'
                        });
                        return;
                    }

                    graphqlErrorsToValidator(this.$validator, error);
                });
        },

        onFileDialogSubmit(url) {
            this.model.url = url;
            this.model.is_uploaded = true;
        },

        showSong() {
            window.location.href = this.model_database.song_lyric.public_url;
        }
    }
};
</script>
