<template>
    <input @change="upload" type="file" />
</template>

<script>
import gql from 'graphql-tag';

const FILE_UPLOAD = gql`
    mutation($file: Upload!) {
        upload_file(file: $file)
    }
`;

export default {
    methods: {
        upload({ target: { files = [] } }) {
            if (!files.length) {
                return;
            }

            console.log(files);

            this.$apollo.mutate({
                mutation: FILE_UPLOAD,
                variables: {
                    file: files[0]
                }
            });
        }
    }
};
</script>
