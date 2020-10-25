<template>
    <v-layout>
        <v-flex xs12 sm9>
            {{ external.url }}<br />
            <span style="color: gray">{{
                external.authors.map(a => a.name).join(', ')
            }}</span>
        </v-flex>
        <v-flex>
            <v-btn color="info" outline @click="updateExternal(external.id)"
                >Upravit</v-btn
            >
        </v-flex>
        <v-flex>
            <v-btn color="error" outline @click="deleteExternal(external.id)"
                >Odstranit</v-btn
            >
        </v-flex>
    </v-layout>
</template>

<script>
import gql from 'graphql-tag';

const DELETE_ITEM = gql`
    mutation DeleteExternal($id: ID!) {
        delete_external(id: $id) {
            id
        }
    }
`;

export default {
    props: ['external'],

    methods: {
        deleteExternal(id) {
            this.$apollo
                .mutate({
                    mutation: DELETE_ITEM,
                    variables: { id: id }
                })
                .then(result => {
                    this.$notify({
                        title: 'Hotovo',
                        text: 'Materiál úspěšně smazán',
                        type: 'success'
                    });

                    this.$emit('delete', id);
                })
                .catch(error => {
                    this.$notify({
                        title: 'Chyba při mazání',
                        text: 'Nepodařilo se vymazat materiál',
                        type: 'error'
                    });
                });
        },

        updateExternal(id) {
            this.$emit('update', id);
        }
    }
};
</script>
