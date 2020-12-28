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
            <h1 class="h2 mb-3">Úprava autora</h1>
            <v-layout row wrap>
                <v-flex xs12 md6>
                    <v-form ref="form">
                        <v-text-field
                            label="Jméno autora"
                            required
                            v-model="model.name"
                            data-vv-name="input.name"
                            :error-messages="errors.collect('input.name')"
                        ></v-text-field>
                        <v-tooltip
                            right
                            :disabled="model.memberships.length == 0"
                        >
                            <template v-slot:activator="{ on }">
                                <div v-on="on">
                                    <v-select
                                        :items="enums.type"
                                        v-model="model.type"
                                        label="Typ"
                                        :readonly="model.memberships.length > 0"
                                    ></v-select>
                                </div>
                            </template>
                            <span
                                >Uvedli jste, že autor je členem nějaké skupiny,
                                proto nelze měnit jeho typ.</span
                            >
                        </v-tooltip>

                        <p v-if="model.memberships.length > 0">
                            Tento autor má nastaveno členství v následujících
                            uskupeních:
                        </p>
                        <p v-for="group in model.memberships" :key="group.id">
                            <b>{{ group.name }}</b>
                        </p>
                        <items-combo-box
                            v-if="model.type !== 0"
                            v-bind:p-items="authors"
                            v-model="model.members"
                            label="Členové – autoři"
                            header-label="Vyberte autora z nabídky nebo vytvořte nového"
                            create-label="Potvrďte enterem a vytvořte nového autora"
                            :multiple="true"
                            :enable-custom="true"
                        ></items-combo-box>
                        <v-textarea
                            outline
                            name="input-7-4"
                            label="Popis autora"
                            v-model="model.description"
                            data-vv-name="input.description"
                            :error-messages="
                                errors.collect('input.description')
                            "
                        ></v-textarea>
                        <items-combo-box
                            v-bind:p-items="tags_period"
                            v-model="model.tags_period"
                            label="Historické období (pro Regenschori)"
                            header-label="Vyberte štítek z nabídky nebo vytvořte nový"
                            create-label="Potvrďte enterem a vytvořte nový štítek"
                            :multiple="true"
                            :enable-custom="false"
                        ></items-combo-box>
                    </v-form>
                </v-flex>
                <v-flex xs12 md6 class="edit-description pl-md-4">
                    <h5>Seznam autorských písní</h5>
                    <v-btn
                        v-for="song_lyric in model.song_lyrics"
                        v-bind:key="song_lyric.id"
                        class="text-none"
                        @click="
                            goToAdminPage('song/' + song_lyric.id + '/edit')
                        "
                        >{{ song_lyric.name }}</v-btn
                    >

                    <p></p>
                    <h5>Seznam materiálů</h5>
                    <v-btn
                        v-for="external in model.externals"
                        v-bind:key="external.id"
                        class="text-none"
                        @click="
                            goToAdminPage('external/' + external.id + '/edit')
                        "
                        >{{ external.public_name }}</v-btn
                    >
                </v-flex>
            </v-layout>
            <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
            <v-btn
                :href="model.public_url"
                class="text-decoration-none mr-0"
                :disabled="isDirty"
                >Zobrazit ve zpěvníku</v-btn
            >
            <v-btn
                :href="model.public_url"
                class="text-decoration-none ml-0"
                target="_blank"
                icon
                ><i class="fas fa-external-link-alt"></i
            ></v-btn>
            <v-btn
                :href="regenschori_url + model.public_route"
                class="text-decoration-none mr-0"
                :disabled="isDirty"
                >Zobrazit v Regenschorim</v-btn
            >
            <v-btn
                :href="regenschori_url + model.public_route"
                class="text-decoration-none ml-0"
                target="_blank"
                icon
                ><i class="fas fa-external-link-alt"></i
            ></v-btn>
            <br /><br />
            <delete-model-dialog
                class-name="Author"
                :model-id="model.id"
                @deleted="is_deleted = true"
                delete-msg="Opravdu chcete vymazat tohoto autora?"
                >Vymazat</delete-model-dialog
            >
            <!-- model deleted dialog -->
            <v-dialog v-model="is_deleted" persistent max-width="290">
                <v-card>
                    <v-card-title class="headline"
                        >Autor byl vymazán</v-card-title
                    >
                    <v-card-text
                        >Autor byl vymazán z databáze, jeho nahrávky, ext.
                        odkazy, písně apod. zůstavají uložené, ale vymazali jsme
                        propojení s tímto autorem.</v-card-text
                    >
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green darken-1"
                            flat
                            @click="goToAdminPage('author')"
                            >Přejít na seznam autorů</v-btn
                        >
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
import Author from 'Admin/models/Author';

const FETCH_AUTHORS = gql`
    query {
        authors(type: 0) {
            id
            name
        }
    }
`;

const FETCH_TAGS_PERIOD = gql`
    query {
        tags_period: tags(type: 10) {
            id
            name
        }
    }
`;

import EditForm from './EditForm';
import { graphqlErrorsToValidator } from 'Admin/helpers/graphValidation';

export default {
    extends: EditForm,

    components: {
        ItemsComboBox,
        DeleteModelDialog
    },

    data() {
        return {
            model: {
                // here goes the definition of model attributes
                id: undefined,
                name: undefined,
                type: undefined,
                description: undefined,
                song_lyrics: [],
                externals: [],
                members: [],
                memberships: [],
                tags_period: []
            },
            enums: {
                type: []
            },
            is_deleted: false,
            fragment: Author.fragment,
            regenschori_url: ''
        };
    },

    apollo: {
        model_database: {
            query: Author.QUERY,
            variables() {
                return Author.getQueryVariables(this.model);
            },

            result(result) {
                this.loadModelDataFromResult(result);
                this.loadEnumJsonFromResult(
                    result,
                    'type_string_values',
                    this.enums.type
                );
            }
        },
        authors: {
            query: FETCH_AUTHORS
        },
        tags_period: {
            query: FETCH_TAGS_PERIOD
        }
    },

    mounted() {
        // console.log(Admin);
        if (document.getElementById('regenschoriUrl')) {
            this.regenschori_url = document.getElementById('regenschoriUrl').getAttribute('value');
        }
    },

    methods: {
        submit() {
            this.$apollo
                .mutate({
                    mutation: Author.MUTATION,
                    variables: Author.getMutationVariables(this.model)
                })
                .then(result => {
                    this.$validator.errors.clear();
                    this.$notify({
                        title: 'Úspěšně uloženo :)',
                        text: 'Autor byl úspěšně uložen',
                        type: 'success'
                    });
                })
                .catch(error => {
                    if (error.graphQLErrors.length == 0) {
                        // unknown error happened
                        this.$notify({
                            title: 'Chyba při ukládání',
                            text: 'Autor nebyl uložen',
                            type: 'error'
                        });
                        return;
                    }

                    graphqlErrorsToValidator(this.$validator, error);
                });
        }
    }
};
</script>
