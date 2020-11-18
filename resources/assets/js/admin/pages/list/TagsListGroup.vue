<template>
    <!-- v-app must wrap all the components -->
    <v-app :dark="$root.dark">
        <notifications />
        <v-container fluid grid-list-xs>
            <create-model
                v-if="allowCreate"
                v-model="search_string"
                class-name="Tag"
                label="Název štítku"
                success-msg="Štítek úspěšně vytvořen"
                :tag-type="typeEnum"
                @saved="$apollo.queries.tags.refetch()"
            ></create-model>
            <v-layout row>
                <v-flex xs12>
                    <v-card>
                        <v-data-table
                            :headers="headers"
                            :items="tags"
                            :search="search_string"
                            :rows-per-page-items="[
                                5,
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
                        >
                            <template v-slot:items="props">
                                <td>
                                    <a
                                        :href="
                                            '/admin/tag/' +
                                                props.item.id +
                                                '/edit'
                                        "
                                        >{{ props.item.name }}</a
                                    >
                                </td>
                                <td class="text-nowrap">
                                    <a
                                        class="text-secondary mr-3"
                                        :href="
                                            '/admin/tag/' +
                                                props.item.id +
                                                '/edit'
                                        "
                                        ><i class="fas fa-pen"></i></a
                                    ><a
                                        v-if="allowCreate"
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

import removeDiacritics from 'Admin/helpers/removeDiacritics';
import CreateModel from 'Admin/components/CreateModel.vue';

const FETCH_TAGS = gql`
    query($type: TagType!) {
        tags: tags_enum(type: $type) {
            id
            name
        }
    }
`;

const delete_item = gql`
    mutation($id: ID!) {
        delete_tag(id: $id) {
            id
        }
    }
`;

export default {
    props: {
        typeEnum: {
            type: String
        },
        allowCreate: {
            type: Boolean,
            default: true
        }
    },

    components: {
        CreateModel
    },

    data() {
        return {
            headers: [
                { text: 'Název', value: 'name' },
                { text: 'Akce', value: 'actions', sortable: false }
            ],
            search_string: ''
        };
    },

    apollo: {
        tags: {
            query: FETCH_TAGS,
            variables() {
                return {
                    type: this.typeEnum
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
                this.deleteTag(id);
            }
        },

        deleteTag(id) {
            this.$apollo
                .mutate({
                    mutation: delete_item,
                    variables: { id }
                })
                .then(result => {
                    this.$notify({
                        title: 'Úspěšně vymazáno',
                        text: 'Štítek byl úspěšně vymazán z databáze',
                        type: 'info'
                    });
                    this.$apollo.queries.tags.refetch();
                })
                .catch(error => {
                    console.log('error');
                });
        },

        buildSearchIndex() {
            for (var item of this.tags) {
                const str = removeDiacritics(item.name).toLowerCase();

                this.$set(item, 'search_index', str);
            }
        }

        // customFilter(items, search) {
        //     const needle = removeDiacritics(search).toLowerCase();

        //     return items.filter(
        //         item => item.search_index.indexOf(needle) !== -1
        //     );
        // }
    }
};
</script>
