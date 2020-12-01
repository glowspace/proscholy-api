<template>
    <!-- v-app must wrap all the components -->
    <v-app :dark="$root.dark">
        <notifications />
        <v-container fluid grid-list-xs>
            <div class="content-header">
                <h1>Autoři</h1>
            </div>
            <create-model
                v-model="search_string"
                class-name="Author"
                label="Jméno autora"
                success-msg="Autor úspěšně vytvořen"
                @saved="$apollo.queries.authors.refetch()"
            ></create-model>
            <v-layout row>
                <v-flex xs12>
                    <v-card>
                        <v-data-table
                            :headers="headers"
                            :items="authors"
                            :search="search_string"
                            :custom-filter="customFilter"
                            :rows-per-page-items="[
                                50,
                                { text: '$vuetify.dataIterator.rowsPerPageAll', value: -1 }
                            ]"
                            :loading="$apollo.loading"
                            :no-data-text="$apollo.loading ? 'Načítám…' : '$vuetify.noDataText'"
                        >
                            <template v-slot:items="props">
                                <td>
                                    <a
                                        :href="'/admin/author/' + props.item.id + '/edit'"
                                        >{{ props.item.name }}</a
                                    >
                                </td>
                                <td>{{ props.item.type_string }}</td>
                                <td class="text-nowrap">
                                    <a
                                        class="text-secondary mr-3"
                                        :href="'/admin/author/' + props.item.id + '/edit'"
                                        ><i class="fas fa-pen"></i></a
                                    ><a
                                        class="text-secondary"
                                        v-on:click="askForm(props.item.id)"
                                        ><i class="fas fa-trash"></i></a
                                    >
                                </td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<style scope>
input {
    border: none;
}
</style>

<script>
import gql from 'graphql-tag';

import removeDiacritics from 'Admin/helpers/removeDiacritics';
import CreateModel from 'Admin/components/CreateModel.vue';

const fetch_items = gql`
    query FetchAuthors {
        authors {
            id
            name
            type_string
        }
    }
`;

const delete_item = gql`
    mutation DeleteAuthor($id: ID!) {
        delete_author(id: $id) {
            id
        }
    }
`;

export default {
    props: ['is-todo'],

    components: {
        CreateModel
    },

    data() {
        return {
            headers: [
                { text: 'Jméno', value: 'name' },
                { text: 'Typ', value: 'type_string' },
                { text: 'Akce', value: 'actions', sortable: false }
            ],
            search_string: ''
        };
    },

    apollo: {
        authors: {
            query: fetch_items,
            variables() {
                return {
                    is_todo: this.isTodo
                };
            },
            result(result) {
                this.buildSearchIndex();
            }
        }
    },

    mounted() {
        if (window.location.hash.length > 2 && this.filter_mode) {
            this.filter_mode = window.location.hash.replace('#', '');
        }

        if (document.getElementById('search')) {
            document.getElementById('search').focus();
        }
    },

    methods: {
        askForm(id) {
            if (confirm('Opravdu chcete smazat daný záznam?')) {
                this.deleteAuthor(id);
            }
        },

        deleteAuthor(id) {
            this.$apollo
                .mutate({
                    mutation: delete_item,
                    variables: { id: id },
                    refetchQueries: [
                        {
                            query: fetch_items
                        }
                    ]
                })
                .then(result => {
                    this.$notify({
                        title: 'Úspěšně vymazáno',
                        text: 'Autor byl úspěšně vymazán z databáze',
                        type: 'info'
                    });
                })
                .catch(error => {
                    console.log('error');
                });
        },

        buildSearchIndex() {
            for (var item of this.authors) {
                // const authors = item.authors.map(a => a.name).join(" ") || (item.has_anonymous_author ? "anonymni" : "");
                const str = removeDiacritics(
                    item.name + ' ' + item.type_string
                ).toLowerCase();

                this.$set(item, 'search_index', str);
            }
        },

        customFilter(items, search) {
            const needle = removeDiacritics(search).toLowerCase();

            return items.filter(
                item => item.search_index.indexOf(needle) !== -1
            );
        }
    }
};
</script>
