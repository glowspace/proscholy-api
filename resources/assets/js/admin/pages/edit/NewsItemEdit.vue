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
            <h1 class="h2 mb-3">Úprava novinky</h1>
            <v-layout row wrap>
                <v-flex xs12 md6>
                    <v-form ref="form">
                        <v-text-field
                            label="Krátký popisek"
                            required
                            v-model="model.text"
                            data-vv-name="input.text"
                            :error-messages="errors.collect('input.text')"
                        ></v-text-field>
                        <v-text-field
                            label="Třída ikony (z Font Awesome, např. 'fas fa-address-card')"
                            required
                            v-model="model.fa_icon"
                            data-vv-name="input.fa_icon"
                            :error-messages="errors.collect('input.fa_icon')"
                            class="mb-1"
                            :hide-details="true"
                        ></v-text-field>
                        <div class="subheading mb-3">
                            <a
                                href="https://fontawesome.com/icons?d=gallery&m=free"
                                target="_blank"
                                >Seznam ikon Font Awesome (třída se zobrazí v detailu ikonky)</a
                            >
                        </div>
                        <v-text-field
                            label="Odkaz"
                            v-model="model.link"
                            data-vv-name="input.link"
                            :error-messages="errors.collect('input.link')"
                        ></v-text-field>
                        <v-select
                            :items="enums.link_type"
                            v-model="model.link_type"
                            label="Typ odkazu"
                        ></v-select>
                        <v-layout justify-space-between wrap>
                            <v-flex xs12 sm6 class="my-3">
                                <div class="subheading">
                                    {{
                                        show_expires_at
                                            ? 'Od'
                                            : 'Plánovaný den zobrazení'
                                    }}
                                </div>
                                <v-date-picker
                                    v-model="model.starts_at"
                                    :min="today()"
                                    locale="cs-cz"
                                    :first-day-of-week="1"
                                ></v-date-picker>
                            </v-flex>
                            <v-flex
                                xs12
                                sm6
                                class="my-3"
                                v-if="show_expires_at"
                            >
                                <div class="subheading">Do</div>
                                <v-date-picker
                                    v-model="model.expires_at"
                                    :min="model.starts_at"
                                    locale="cs-cz"
                                    :first-day-of-week="1"
                                ></v-date-picker>
                            </v-flex>
                        </v-layout>
                        <v-checkbox
                            v-model="show_expires_at"
                            label="Více dní"
                        ></v-checkbox>
                        <v-checkbox
                            v-model="model.is_published"
                            label="Publikováno (zobrazí se na produkci v plánované dny)"
                        ></v-checkbox>
                    </v-form>
                </v-flex>
            </v-layout>
            <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
            <br />
            <br />
            <delete-model-dialog
                class-name="NewsItem"
                :model-id="model.id || null"
                @deleted="is_deleted = true"
                delete-msg="Opravdu chcete vymazat tento záznam?"
                >Vymazat</delete-model-dialog
            >
            <!-- model deleted dialog -->
            <v-dialog v-model="is_deleted" persistent max-width="320">
                <v-card>
                    <v-card-title class="headline"
                        >Záznam byl vymazán</v-card-title
                    >
                    <v-card-text>Záznam byl vymazán z databáze.</v-card-text>
                    <v-card-actions
                        class="d-flex flex-column justify-content-end"
                    >
                        <v-spacer></v-spacer>
                        <div>
                            <v-btn
                                color="green darken-1"
                                flat
                                @click="goToAdminPage('news-item', false)"
                                >Přejít na seznam novinek</v-btn
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
import DeleteModelDialog from 'Admin/components/DeleteModelDialog.vue';

import EditForm from './EditForm';
import NewsItem from 'Admin/models/NewsItem';

export default {
    components: {
        DeleteModelDialog
    },
    extends: EditForm,

    data() {
        return {
            model: {
                // here goes the definition of model attributes
                id: undefined,
                text: undefined,
                fa_icon: undefined,
                link_type: undefined,
                starts_at: undefined,
                expires_at: undefined,
                is_published: undefined
            },
            enums: {
                link_type: []
            },
            fragment: NewsItem.fragment,
            is_deleted: false,
            show_expires_at: false
        };
    },

    apollo: {
        model_database: {
            query: NewsItem.QUERY,
            variables() {
                return NewsItem.getQueryVariables(this.model);
            },

            result(result) {
                this.loadModelDataFromResult(result);
                this.loadEnumJsonFromResult(
                    result,
                    'link_type_string_values',
                    this.enums.link_type
                );

                function transformDate(date) {
                    date = date || this.today();
                    return date.substr(0, 10);
                }

                this.transformFields(
                    ['starts_at', 'expires_at'],
                    transformDate
                );

                if (this.model.starts_at != this.model.expires_at) {
                    this.show_expires_at = true;
                }
            }
        }
    },

    methods: {
        submit() {
            this.$apollo
                .mutate({
                    mutation: NewsItem.MUTATION,
                    variables: NewsItem.getMutationVariables(this.model)
                })
                .then(result => {
                    this.$validator.errors.clear();
                    this.$notify({
                        title: 'Úspěšně uloženo :)',
                        text: 'Novinka byla úspěšně uložena',
                        type: 'success'
                    });
                })
                .catch(error => {
                    if (error.graphQLErrors.length == 0) {
                        // unknown error happened
                        this.$notify({
                            title: 'Chyba při ukládání',
                            text: 'Novinka nebyla uložena',
                            type: 'error'
                        });
                        return;
                    }

                    this.handleValidationErrors(error);
                });
        },

        syncDateTimes() {
            if (!this.show_expires_at) {
                this.model.expires_at = this.model.starts_at;
            }
        },

        today() {
            return new Date().toISOString().substr(0, 10);
        }
    },

    watch: {
        'model.starts_at': function() {
            this.syncDateTimes();
        },
        show_expires_at: function() {
            this.syncDateTimes();
        }
    }
};
</script>
