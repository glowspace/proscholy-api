<template>
    <!-- v-app must wrap all the components -->
    <v-app :dark="$root.dark">
        <notifications />
        <v-container fluid grid-list-xs>
            <h1 class="h2 mb-3">Nahrané soubory</h1>
            <v-btn
                href="/admin/file/create"
                class="ml-0 text-decoration-none primary"
                >+ Nahrát nový soubor</v-btn
            >
            <v-layout row wrap>
                <v-flex xs12 md8>
                    <v-radio-group v-model="filter_mode">
                        <v-radio
                            label="Všechny soubory"
                            value="no-filter"
                        ></v-radio>
                        <v-radio
                            label="Soubory bez autora / přiřazené písničky"
                            value="filter-todo"
                        ></v-radio>
                    </v-radio-group>
                </v-flex>
                <v-flex xs12 md4>
                    <v-text-field
                        v-model="search_string"
                        label="Vyhledávání"
                        prepend-icon="search"
                        @click:prepend="$refs.search.focus()"
                        ref="search"
                        :clearable="true"
                        id="search"
                        autofocus
                    ></v-text-field>
                </v-flex>
            </v-layout>
            <v-layout row>
                <v-flex xs12>
                    <v-card>
                        <v-data-table
                            :headers="headers"
                            :items="files"
                            :search="search_string"
                            :custom-filter="customFilter"
                            :rows-per-page-items="[
                                50,
                                {
                                    text:
                                        '$vuetify.dataIterator.rowsPerPageAll',
                                    value: -1
                                }
                            ]"
                            :loading="$apollo.loading"
                            :no-data-text="
                                $apollo.loading
                                    ? 'Načítám…'
                                    : '$vuetify.noDataText'
                            "
                            :pagination.sync="dtPagination"
                        >
                            <template v-slot:items="props">
                                <td>
                                    <a
                                        :href="
                                            '/admin/file/' +
                                                props.item.id +
                                                '/edit'
                                        "
                                        >{{ props.item.public_name }}</a
                                    >
                                </td>
                                <td>{{ props.item.type_string }}</td>
                                <td>
                                    {{
                                        props.item.song_lyric
                                            ? props.item.song_lyric.name
                                            : '–'
                                    }}
                                </td>
                                <td>
                                    {{
                                        props.item.authors
                                            .map(a => a.name)
                                            .join(', ') || '–'
                                    }}
                                </td>
                                <td class="text-nowrap">
                                    <a
                                        class="text-secondary mr-3"
                                        :href="props.item.download_url"
                                        ><i class="fas fa-download"></i></a
                                    ><a
                                        class="text-secondary mr-3"
                                        :href="
                                            '/admin/file/' +
                                                props.item.id +
                                                '/edit'
                                        "
                                        ><i class="fas fa-pen"></i></a
                                    ><a
                                        class="text-secondary"
                                        v-on:click="askForm(props.item.id)"
                                        ><i class="fas fa-trash"></i
                                    ></a>
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

import FileUpload from 'Admin/components/FileUpload';
import removeDiacritics from 'Admin/helpers/removeDiacritics';

const fetch_items = gql`
    query FetchFiles($is_todo: Boolean) {
        files(is_todo: $is_todo) {
            id
            public_name
            type_string
            download_url
            song_lyric {
                name
            }
            authors {
                name
            }
        }
    }
`;

const delete_item = gql`
    mutation DeleteFile($id: ID!) {
        delete_file(id: $id) {
            id
        }
    }
`;

export default {
    components: { FileUpload },

    data() {
        return {
            headers: [
                { text: 'Název', value: 'public_name' },
                { text: 'Typ', value: 'type_string' },
                { text: 'Píseň', value: 'song_lyric' },
                { text: 'Autoři', value: 'authors' },
                { text: 'Akce', value: 'actions', sortable: false }
            ],
            search_string: '',
            filter_mode: 'no-filter',
            dtPagination: {}
        };
    },

    watch: {
        filter_mode(val) {
            window.location.hash = val != 'no-filter' ? val : '';
            this.dtPagination.page = 1;
        }
    },

    apollo: {
        files: {
            query: fetch_items,
            variables() {
                return {
                    is_todo:
                        this.filter_mode == 'filter-todo' ? true : undefined
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

        if (
            window.location.hash == '#n' &&
            document.getElementById('create-model-text-field')
        ) {
            document.getElementById('create-model-text-field').focus();
        } else if (document.getElementById('search')) {
            document.getElementById('search').focus();
        }
    },

    methods: {
        askForm(id) {
            if (confirm('Opravdu chcete smazat daný záznam?')) {
                this.deleteFile(id);
            }
        },

        deleteFile(id) {
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
                    console.log('uspesne vymazano');
                })
                .catch(error => {
                    console.log('error');
                });
        },

        buildSearchIndex() {
            for (var item of this.files) {
                const authors = item.authors.map(a => a.name).join(' ');
                const str = removeDiacritics(
                    [
                        item.public_name,
                        authors,
                        item.song_lyric ? item.song_lyric.name : '',
                        item.type_string
                    ].join(' ')
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
