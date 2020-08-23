<template>
    <!-- v-app must wrap all the components -->
    <v-app :dark="$root.dark">
        <notifications />
        <v-container fluid grid-list-xs>
            <h1 class="h2 mb-3">Novinky</h1>
            <create-model
                class-name="NewsItem"
                label="Zadejte url adresu nové položky v novinkách"
                success-msg="Novinka úspěšně vytvořena"
                @saved="$apollo.queries.news_items.refetch()"
                :force-edit="true"
            ></create-model>
            <v-layout row wrap>
                <v-flex xs12 md8>
                    <v-radio-group v-model="filter_mode">
                        <v-radio
                            label="Všechny novinky"
                            value="no-filter"
                        ></v-radio>
                        <v-radio
                            label="Pouze aktivní"
                            value="filter-active"
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
                            :items="news_items"
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
                                            '/admin/news-item/' +
                                                props.item.id +
                                                '/edit'
                                        "
                                        >{{ getShortUrl(props.item.link) }}</a
                                    >
                                </td>
                                <td>
                                    <i
                                        v-if="props.item.fa_icon"
                                        :class="`fa fa-${props.item.fa_icon}`"
                                    ></i>
                                    {{ props.item.text }}
                                </td>
                                <td>
                                    {{
                                        props.item.starts_at
                                            ? new Date(
                                                  props.item.starts_at
                                              ).toLocaleString('cs-CZ')
                                            : '–'
                                    }}
                                </td>
                                <td>
                                    {{
                                        props.item.expires_at
                                            ? new Date(
                                                  props.item.expires_at
                                              ).toLocaleString('cs-CZ')
                                            : '–'
                                    }}
                                </td>
                                <td>
                                    {{ props.item.is_published ? 'ano' : 'ne' }}
                                </td>
                                <td class="text-nowrap">
                                    <a
                                        class="text-secondary mr-3"
                                        :href="
                                            '/admin/news-item/' +
                                                props.item.id +
                                                '/edit'
                                        "
                                        ><i class="fas fa-pen"></i
                                    ></a>
                                    <a
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

const fetch_items = gql`
    query($active: Boolean) {
        news_items(active: $active) {
            id
            text
            fa_icon
            link
            link_type
            is_published
            starts_at
            expires_at
        }
    }
`;

export default {
    components: {
        CreateModel
    },

    data() {
        return {
            headers: [
                { text: 'Adresa', value: 'link' },
                { text: 'Text', value: 'text' },
                { text: 'Začátek', value: 'starts_at' },
                { text: 'Konec', value: 'expires_at' },
                { text: 'Publikováno', value: 'is_published' },
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
        news_items: {
            query: fetch_items,
            variables() {
                return {
                    active:
                        this.filter_mode == 'filter-active' ? true : undefined
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
        getShortUrl(url) {
            if (!url) return;

            const bare = url
                .replace('http://', '')
                .replace('https://', '')
                .replace('www.', '');
            if (bare.length < 50) return bare;

            const head = bare.substring(0, 15);
            const tail = bare.substring(bare.length - 15, bare.length);

            return head + '...' + tail;
        },

        buildSearchIndex() {
            for (var item of this.news_items) {
                const str = removeDiacritics(
                    [
                        item.link,
                        item.text
                        // item.type_string
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
