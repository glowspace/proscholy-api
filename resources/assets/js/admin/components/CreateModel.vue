<template>
    <v-card class="mb-3 px-4 py-3">
        <v-layout row>
            <div class="text-nowrap pt-1" v-if="enableFileUpload">
                <FileUploadDialog
                    @submit="onFileDialogSubmit"
                ></FileUploadDialog>
                <svg class="text-very-muted mt-2" style="height:3rem;transform:rotate(80deg);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M594.53 508.63L6.18 53.9c-6.97-5.42-8.23-15.47-2.81-22.45L23.01 6.18C28.43-.8 38.49-2.06 45.47 3.37L633.82 458.1c6.97 5.42 8.23 15.47 2.81 22.45l-19.64 25.27c-5.42 6.98-15.48 8.23-22.46 2.81z"></path></svg>
            </div>
            <v-text-field
                :label="label"
                required
                v-model="attribute_value"
                data-vv-name="required_attribute"
                :error-messages="errors.collect('required_attribute')"
                prepend-icon="add"
                @click:prepend="$refs.cmtf.focus()"
                ref="cmtf"
                class="mt-0 pb-0 pt-3"
                style="max-width:600px;width:50vw"
                @keydown.enter="submit(true)"
                @input="$validator.errors.clear()"
                id="create-model-text-field"
            ></v-text-field>
            <div class="text-nowrap pt-1">
                <v-btn
                    :disabled="attribute_value == '' || saving"
                    @click="submit(true)"
                    color="primary"
                    style="margin-left:33px"
                    >Vytvořit a upravit</v-btn
                >
                <v-btn
                    v-if="!forceEdit"
                    :disabled="attribute_value == '' || saving"
                    @click="submit(false)"
                    >Vytvořit</v-btn
                >
            </div>
        </v-layout>
    </v-card>
</template>

<script>
import gql, { disableFragmentWarnings } from 'graphql-tag';
import FileUploadDialog from 'Admin/components/FileUploadDialog';
import { graphqlErrorsToValidator } from 'Admin/helpers/graphValidation';

const CREATE_MODEL_MUTATION = gql`
    mutation($input: CreateModelInput!) {
        create_model(input: $input) {
            id
            edit_url
        }
    }
`;

export default {
    props: [
        'class-name',
        'label',
        'success-msg',
        'force-edit',
        'enable-file-upload'
    ],

    components: { FileUploadDialog },

    data() {
        return {
            attribute_value: '',
            saving: false
        };
    },

    methods: {
        submit(redir) {
            this.saving = true;
            this.$apollo
                .mutate({
                    mutation: CREATE_MODEL_MUTATION,
                    variables: {
                        input: {
                            required_attribute: this.attribute_value,
                            class_name: this.className
                        }
                    }
                })
                .then(result => {
                    this.saving = false;
                    this.$notify({
                        title: 'Hotovo :)',
                        text: this.successMsg,
                        type: 'success'
                    });

                    if (redir) {
                        window.location.href =
                            result.data.create_model.edit_url;
                    } else {
                        this.$emit('saved');
                        this.attribute_value = '';
                        document
                            .getElementById('create-model-text-field')
                            .focus();
                    }
                })
                .catch(error => {
                    this.saving = false;
                    if (
                        !error.graphQLErrors ||
                        error.graphQLErrors.length == 0
                    ) {
                        // unknown error happened
                        this.$notify({
                            title: 'Chyba při ukládání',
                            text: 'Položka nebyla uložena (' + error + ')',
                            type: 'error'
                        });
                        return;
                    }

                    graphqlErrorsToValidator(this.$validator, error);
                });
        },

        onFileDialogSubmit(url) {
            this.attribute_value = url;
            this.submit(this.forceEdit);
        }
    },

    $_veeValidate: {
        validator: 'new'
    }
};
</script>
