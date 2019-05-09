<template>
    <v-app>

    </v-app>
</template>

<script>

import gql from 'graphql-tag';

const fetch_item = gql`
        query {
            author($id: ID!) {
                id,
                name,
                type_string,
                description
            }
        }`;

export default {
    props: ['id'],

    data() {
        return {
        }
    },

    apollo: {
        author: { 
        query: fetch_item,
            variables() {
                return { 
                    id: this.id,
                }
            }
        }
    },

    methods: {

    }
}
</script>
