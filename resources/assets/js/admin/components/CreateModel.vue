<template>
    <v-layout row wrap>
        <v-text-field
            :label="label"
            required
            v-model="attribute_value"
            data-vv-name="required_attribute"
            :error-messages="errors.collect('required_attribute')"
            prepend-icon="add"
            class="w-100"
            style="max-width:600px"
            @keydown.enter="submit(true)"
            id="create-model-text-field"
        ></v-text-field>
        <div class="text-nowrap mt-1">
            <v-btn :disabled="attribute_value == '' || saving" @click="submit(true)" color="primary" style="margin-left:33px"
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
</template>

<script>
import gql, { disableFragmentWarnings } from 'graphql-tag';

const CREATE_MODEL_MUTATION = gql`
    mutation($input: CreateModelInput!) {
        create_model(input: $input) {
            id
            edit_url
        }
    }
`;

export default {
    props: ['class-name', 'label', 'success-msg', 'force-edit'],

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
                        document.getElementById('create-model-text-field').focus();
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

                    let errorFields =
                        error.graphQLErrors[0].extensions.validation;

                    // clear the old errors and (add new ones if exist)
                    this.$validator.errors.clear();
                    for (const [key, value] of Object.entries(errorFields)) {
                        this.$validator.errors.add({ field: key, msg: value });
                    }
                });
        }
    },

    $_veeValidate: {
        validator: 'new'
    }
};
</script>
