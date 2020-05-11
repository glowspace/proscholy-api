<template>
    <div>
        <v-text-field
            :label="label"
            required
            v-model="attribute_value"
            data-vv-name="required_attribute"
            :error-messages="errors.collect('required_attribute')"
        ></v-text-field>
        <v-btn
            v-if="!forceEdit"
            :disabled="attribute_value == ''"
            @click="submit(false)"
            >Vytvořit</v-btn
        >
        <v-btn :disabled="attribute_value == ''" @click="submit(true)"
            >Vytvořit a editovat</v-btn
        >
    </div>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";

const CREATE_MODEL_MUTATION = gql`
    mutation($input: CreateModelInput!) {
        create_model(input: $input) {
            id
            edit_url
        }
    }
`;

export default {
    props: ["class-name", "label", "success-msg", "force-edit"],

    data() {
        return {
            attribute_value: ""
        };
    },

    methods: {
        submit(redir) {
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
                    this.$notify({
                        title: "Hotovo :)",
                        text: this.successMsg,
                        type: "success"
                    });

                    if (redir) {
                        window.location.href =
                            result.data.create_model.edit_url;
                    } else {
                        this.$emit("saved");
                        this.attribute_value = "";
                    }
                })
                .catch(error => {
                    if (
                        !error.graphQLErrors ||
                        error.graphQLErrors.length == 0
                    ) {
                        // unknown error happened
                        this.$notify({
                            title: "Chyba při ukládání",
                            text: "Položka nebyla uložena (" + error + ")",
                            type: "error"
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
        validator: "new"
    }
};
</script>
