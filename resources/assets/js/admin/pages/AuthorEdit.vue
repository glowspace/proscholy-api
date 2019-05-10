<template>
    <v-app>
        <v-container grid-list-xs>
            <v-layout row>
                <v-flex xs12 md6>
                    <v-form>
                        <v-text-field label="Jméno autora" required v-model="name"></v-text-field>
                        <v-select
                            :items="type_values"
                            v-model="type"
                            label="Typ"
                        ></v-select>
                        <v-textarea
                            name="input-7-4"
                            label="Popis autora"
                            v-model="description">
                        </v-textarea>

                        <v-btn @click="submit">Uložit</v-btn>
                    </v-form>
                </v-flex>
                <v-flex xs12 md6>

                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>

import gql from 'graphql-tag';

const fetch_item = gql`
    query ($id: ID!) {
        author(id: $id) {
            id,
            name,
            type,
            type_string_values,
            description
        }
    }`;

const update_item = gql`
    mutation ($id: ID!, $name: String!, $description: String, $type: Int!) {
        update_author(id: $id, name: $name, description: $description, type: $type) {
            id
        }
    }
`;

export default {
    props: ['id'],

    data() {
        return {
            type: undefined,
            type_values: [],
            description: "",
            name: ""
        }
    },

    apollo: {
        author: { 
            query: fetch_item,
            variables() {
                return { 
                    id: this.id,
                }
            },
            result: function result(result) {
                let author = result.data.author;
                console.log(author);
                // one-way copy the data
                this.description = author.description;
                this.name = author.name;
                this.type = author.type;
                this.type_values = author.type_string_values.map((val, index) => {
                    return {value: index, text: val};
                });
            }
        },
    },

    computed: {

    },

    methods: {
        submit() {
            this.$apollo.mutate({
                mutation: update_item,
                variables: {
                    id: this.id,
                    name: this.name,
                    description: this.description,
                    type: this.type
                },
                // refetchQueries: [{
                //     query: fetch_item
                // }]
            }).then((result) => {
                console.log(result);
                console.log('uspesne upraveno');
            }).catch((error) => {
                console.log('error');
            });
        }
    }
}
</script>
