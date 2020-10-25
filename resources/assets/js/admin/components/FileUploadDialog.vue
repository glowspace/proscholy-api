<template>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }">
            <v-btn outline color="primary" dark v-on="on">{{
                btnCaption
            }}</v-btn>
        </template>

        <v-card>
            <v-card-title class="headline">Nahrát soubor</v-card-title>
            <v-card-text>
                <v-text-field
                    label="Název souboru"
                    v-model="filename"
                    prepend-icon="attach_file"
                    data-vv-name="input.filename"
                    :error-messages="errors.collect('input.filename')"
                    @input="$validator.errors.clear()"
                ></v-text-field>
                <p v-if="file">
                    Velikost souboru: {{ prettyBytes(file.size) }}
                </p>
                <!-- Hidden -->
                <input
                    type="file"
                    style="display: none"
                    ref="fileInput"
                    accept="*/*"
                    @change="onFilePicked"
                />
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="red darken-1" flat @click="onCancel"
                    >Zrušit</v-btn
                >

                <v-btn @click="onSubmit" class="primary" :disabled="!file"
                    ><span v-if="is_uploading">
                        <span
                            class="spinner-border spinner-border-sm"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        Nahrávání... </span
                    ><span v-else>Nahrát soubor</span></v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import gql from 'graphql-tag';
import slugify from 'slugify';
import prettyBytes from 'pretty-bytes';
import { graphqlErrorsToValidator } from 'Admin/helpers/graphValidation';

const FILE_UPLOAD = gql`
    mutation($file: Upload!, $filename: String!) {
        upload_file(file: $file, filename: $filename)
    }
`;

export default {
    props: {
        btnCaption: {
            type: String,
            default: 'Nahrát soubor'
        }
    },

    data() {
        return {
            dialog: false,
            file: null,
            filename: '',
            baseUrl: document.querySelector('#baseUrl').getAttribute('value'),
            is_uploading: false
        };
    },

    $_veeValidate: {
        validator: 'new'
    },

    methods: {
        onCancel() {
            this.dialog = false;
        },

        async onSubmit() {
            this.is_uploading = true;

            this.uploadSelectedFile()
                .then(result => {
                    this.dialog = false;
                    this.$emit(
                        'submit',
                        this.baseUrl + '/soubor/' + this.filename
                    );
                })
                .catch(error => {
                    this.$notify({
                        title: 'Chyba při nahrávání souboru',
                        text: 'Soubor se nepodařilo nahrát',
                        type: 'error'
                    });

                    if (error.graphQLErrors) {
                        graphqlErrorsToValidator(this.$validator, error);
                    }
                    this.is_uploading = false;
                });
        },

        onFilePicked({ target: { files = [] } }) {
            if (!files.length) {
                this.onCancel();
                return;
            }

            this.file = files[0];
            this.filename = this.sluggedName(files[0].name);
        },

        uploadSelectedFile() {
            return this.$apollo.mutate({
                mutation: FILE_UPLOAD,
                variables: {
                    file: this.file,
                    filename: this.filename
                }
            });
        },

        // helper methods

        sluggedName(name) {
            return slugify(name, '-')
                .toLowerCase()
                .replaceAll('_', '-');
        },

        prettyBytes: prettyBytes
    },

    watch: {
        dialog() {
            if (this.dialog) {
                this.file = null;
                this.filename = '';
                this.is_uploading = false;
                this.$validator.errors.clear();
                this.$refs.fileInput.click();
            }
        }
    }
};
</script>
