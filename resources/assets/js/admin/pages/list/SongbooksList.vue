<template>
    <!-- v-app must wrap all the components -->
    <v-app>
        <notifications />
        <v-container fluid grid-list-xs>
            <create-model
                class-name="Songbook"
                label="Zadejte jméno nového zpěvníku"
                success-msg="Zpěvník úspěšně vytvořen"
                @saved="$apollo.queries.songbooks.refetch()"
                :force-edit="true"
            ></create-model>
            <v-layout row>
                <v-flex xs12 md4 offset-md8>
                    <v-text-field
                        v-model="search_string"
                        label="Vyhledávání"
                        prepend-icon="search"
                    ></v-text-field>
                </v-flex>
            </v-layout>
            <v-layout row>
                <v-flex xs12>
                    <v-data-table
                        :headers="headers"
                        :items="songbooks"
                        :search="search_string"
                        :filter="formFilter"
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
                                    :href="
                                        '/admin/songbook/' +
                                            props.item.id +
                                            '/edit'
                                    "
                                    >{{ props.item.name }}</a
                                >
                            </td>
                            <td>{{ props.item.shortcut }}</td>
                            <td>
                                {{
                                    props.item.is_private
                                        ? 'interní'
                                        : 'veřejný'
                                }}
                            </td>
                            <td>{{ props.item.records.length }}</td>
                            <td>
                                <a
                                    href="#"
                                    style="color:red"
                                    v-on:click="askForm(props.item.id)"
                                    >Vymazat</a
                                >
                            </td>
                        </template>
                    </v-data-table>
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
    query {
        songbooks {
            id
            name
            shortcut
            records {
                id
            }
            is_private
        }
    }
`;

const delete_item = gql`
    mutation DeleteSongbook($id: ID!) {
        delete_songbook(id: $id) {
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
                { text: 'Zkratka', value: 'shortcut' },
                { text: 'Typ', value: 'is_private' },
                { text: 'Počet záznamů', value: 'records.length' },
                { text: 'Akce', value: 'actions', sortable: false }
            ],
            search_string: ''
        };
    },

    apollo: {
        songbooks: {
            query: fetch_items
        }
    },

    methods: {
        askForm(id) {
            if (confirm('Opravdu chcete smazat daný záznam?')) {
                this.deleteItem(id);
            }
        },

        deleteItem(id) {
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
                        text: 'Zpěvník byl úspěšně vymazán z databáze',
                        type: 'info'
                    });
                })
                .catch(error => {
                    console.log('error');
                });
        },

        formFilter(val, search) {
            if (typeof val == 'string') {
                let hay = removeDiacritics(val).toLowerCase();
                let needle = removeDiacritics(search).toLowerCase();

                return hay.indexOf(needle) >= 0;
            }

            return false;
        }
    }
};
</script>
