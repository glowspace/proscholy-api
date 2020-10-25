<template>
    <v-card class="mb-4 px-4">
        <v-card-title class="p-0">
            <h3>Rychlé přidání materiálu</h3>
        </v-card-title>
        <v-card-text class="p-0">
            <v-layout row wrap>
                <v-flex xs4>
                    <v-text-field
                        label="URL"
                        v-model="new_external.url"
                    ></v-text-field>
                </v-flex>
                <v-flex xs2>
                    nebo
                    <FileUploadDialog
                        v-on:submit="onFileDialogSubmit"
                    ></FileUploadDialog>
                </v-flex>
                <v-flex>
                    <items-combo-box
                        v-model="new_external.authors"
                        v-bind:p-items="authors"
                        item-text="name"
                        label="Autoři materiálu"
                        :multiple="true"
                        :enable-custom="true"
                    ></items-combo-box>
                </v-flex>
            </v-layout>
            <v-layout>
                <v-flex xs4>
                    <v-btn
                        color="info"
                        outline
                        @click="createNewExternal()"
                        :disabled="new_external.url.length == 0"
                        >Přidat nový materiál</v-btn
                    >
                </v-flex>
            </v-layout>
        </v-card-text>
    </v-card>
</template>

<script>
import gql, { disableFragmentWarnings } from 'graphql-tag';
import FileUploadDialog from 'Admin/components/FileUploadDialog';
import ItemsComboBox from 'Admin/components/ItemsComboBox.vue';
import { belongsToManyMutator } from 'Admin/models/relations';

// ok not quite sure if this belongs here, but
// so far, this component is used only for SongLyrics ¯\_(ツ)_/¯
import SongLyric from 'Admin/models/SongLyric.js';

const CREATE_EXTERNAL_MUTATION = gql`
    mutation($input: CreateExternalInput!) {
        create_external(input: $input) {
            ...ExternalFragment
        }
    }
    ${SongLyric.external_fragment}
`;

const FETCH_DATA = gql`
    query {
        authors(order_last_associated: true) {
            id
            name
        }
    }
`;

export default {
    props: {
        songLyricId: Number
    },

    components: {
        FileUploadDialog,
        ItemsComboBox
    },

    data() {
        return {
            new_external: {
                url: '',
                authors: []
            }
        };
    },

    apollo: {
        authors: {
            query: FETCH_DATA
        }
    },

    methods: {
        createNewExternal() {
            return this.$apollo
                .mutate({
                    mutation: CREATE_EXTERNAL_MUTATION,
                    variables: {
                        input: {
                            url: this.new_external.url,
                            authors: belongsToManyMutator(
                                this.new_external.authors
                            ),
                            song_lyric: {
                                connect: this.songLyricId
                            }
                        }
                    }
                })
                .then(result => {
                    this.$validator.errors.clear();
                    this.$notify({
                        title: 'Úspěšně uloženo :)',
                        text: 'Materiál úspěšně přidán',
                        type: 'success'
                    });

                    this.$emit('create', result.data.create_external);
                })
                .catch(error => {
                    this.$notify({
                        title: 'Chyba při ukládání',
                        text: 'Nepovedlo se přidat materiál',
                        type: 'error'
                    });
                });
        },

        onFileDialogSubmit(url) {
            this.new_external.url = url;
        }
    }
};
</script>
