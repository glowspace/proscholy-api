<template>
    <div>
        <v-text-field
            :label="file ? 'Název souboru' : 'Zvolit soubor...'"
            @click="onPickFile"
            v-model="filename"
            prepend-icon="attach_file"
            style="cursor: hand"
        ></v-text-field>
        <p v-if="file">Velikost souboru: {{ prettyBytes(file.size) }}</p>
        <!-- Hidden -->
        <input
            type="file"
            style="display: none"
            ref="fileInput"
            accept="*/*"
            @change="onFilePicked"
        />
        <v-btn @click="upload" class="primary">Nahrát soubor</v-btn>
    </div>
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
            file: null,
            filename: ''
        };
    },

    methods: {
        onPickFile() {
            this.$refs.fileInput.click();
        },

        onFilePicked({ target: { files = [] } }) {
            if (!files.length) {
                return;
            }

            console.log(files[0]);

            this.file = files[0];
            this.filename = this.sluggedName(files[0].name);
        },

        upload() {
            this.$apollo.mutate({
                mutation: FILE_UPLOAD,
                variables: {
                    file: this.file,
                    filename: this.filename
                }
            });
        },

        // helper method

        sluggedName(name) {
            return slugify(name, '-')
                .toLowerCase()
                .replaceAll('_', '-');
        },

        prettyBytes: prettyBytes
    }
};
</script>
