<template>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }">
            <v-btn outline color="primary" dark v-on="on">Nahrát soubor</v-btn>
        </template>

        <v-card>
            <v-card-title class="headline">Nahrát soubor</v-card-title>
            <v-card-text>
                <v-text-field
                    label="Název souboru"
                    v-model="filename"
                    prepend-icon="attach_file"
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
                <v-btn color="green darken-1" flat @click="onCancel"
                    >Zrušit</v-btn
                >

                <v-btn @click="onSubmit" class="primary" :disabled="!file"
                    >Nahrát soubor</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import gql from 'graphql-tag';
import slugify from 'slugify';
import prettyBytes from 'pretty-bytes';

const FILE_UPLOAD = gql`
    mutation($file: Upload!) {
        upload_file(file: $file)
    }
`;

export default {
    data() {
        return {
            dialog: false,
            file: null,
            filename: '',
            baseUrl: document.querySelector('#baseUrl').getAttribute('value')
        };
    },

    methods: {
        onCancel() {
            this.dialog = false;
        },

        async onSubmit() {
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
                    return;
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
                this.$refs.fileInput.click();
            }
        }
    }
};
</script>
