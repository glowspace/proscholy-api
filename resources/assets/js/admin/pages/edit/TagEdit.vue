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
            <h1 class="h2 mb-3">Úprava štítku</h1>
            <v-layout row wrap>
                <v-flex xs12 md6>
                    <v-form ref="form">
                        <v-text-field
                            label="Název"
                            required
                            v-model="model.name"
                            data-vv-name="input.name"
                            :error-messages="errors.collect('input.name')"
                        ></v-text-field>

                        <v-textarea
                            outline
                            name="input-7-4"
                            label="Popisek šítku"
                            v-model="model.description"
                            data-vv-name="input.description"
                            :error-messages="
                                errors.collect('input.description')
                            "
                        ></v-textarea>

                        <v-checkbox
                            v-if="model_database && model_database.is_for_songs"
                            v-model="model.hide_in_liturgy"
                            label="Skrýt ve filtrech pro liturgické doporučení"
                        ></v-checkbox>
                    </v-form>
                </v-flex>
                <v-flex xs12 md6>
                    <!-- ... -->
                </v-flex>
            </v-layout>
            <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
            <br />
            <br />
            <delete-model-dialog
                v-if="canDelete"
                class-name="Tag"
                :model-id="model.id || null"
                @deleted="is_deleted = true"
                delete-msg="Opravdu chcete vymazat tento štítek?"
                >Vymazat</delete-model-dialog
            >
            <!-- model deleted dialog -->
            <v-dialog v-model="is_deleted" persistent max-width="320">
                <v-card>
                    <v-card-title class="headline"
                        >Štítek byl vymazán</v-card-title
                    >
                    <v-card-text>Štítek byl vymazán z databáze.</v-card-text>
                    <v-card-actions
                        class="d-flex flex-column justify-content-end"
                    >
                        <v-spacer></v-spacer>
                        <div>
                            <v-btn
                                color="green darken-1"
                                flat
                                @click="goToAdminPage('tag', false)"
                                >Přejít na seznam štítků</v-btn
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
// import ItemsComboBox from 'Admin/components/ItemsComboBox.vue';
import DeleteModelDialog from 'Admin/components/DeleteModelDialog.vue';

import EditForm from './EditForm';
import Tag from 'Admin/models/Tag';
import { graphqlErrorsToValidator } from 'Admin/helpers/graphValidation';

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
                name: undefined,
                description: undefined,
                hide_in_liturgy: undefined
            },
            fragment: Tag.fragment,
            is_deleted: false
        };
    },

    apollo: {
        model_database: {
            query: Tag.QUERY,
            variables() {
                return Tag.getQueryVariables(this.model);
            },

            result(result) {
                this.loadModelDataFromResult(result);
            }
        }
    },

    methods: {
        submit() {
            this.$apollo
                .mutate({
                    mutation: Tag.MUTATION,
                    variables: Tag.getMutationVariables(this.model)
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
        }
    },

    computed: {
        canDelete() {
            if (!this.model_database) return false;

            const group = this.model_database.groups_info.find(
                g => g.type == this.model_database.type_enum
            );
            return group.is_editable;
        }
    }
};
</script>
