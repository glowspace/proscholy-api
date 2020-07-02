<template>
    <!-- v-app must wrap all the components -->
    <v-app>
        <notifications />
        <v-container fluid grid-list-xs>
            <create-model
                class-name="SongLyric"
                label="Zadejte jméno nové písně"
                success-msg="Píseň úspěšně vytvořena"
                @saved="$apollo.queries.song_lyrics.refetch()"
            ></create-model>
            <v-layout row>
                <v-flex xs7>
                    <v-radio-group v-model="filter_mode">
                        <v-radio
                            label="Všechny písně"
                            value="no-filter"
                        ></v-radio>
                        <v-radio
                            label="Písně bez textu"
                            value="no-lyrics"
                        ></v-radio>
                        <v-radio
                            label="Písně bez akordů"
                            value="no-chords"
                        ></v-radio>
                        <v-radio
                            label="Písně bez autora"
                            value="no-author"
                        ></v-radio>
                        <v-radio
                            label="Písně bez štítků"
                            value="no-tags"
                        ></v-radio>
                    </v-radio-group>
                </v-flex>
                <v-flex xs5>
                    <v-text-field
                        v-model="search_string"
                        label="Vyhledávání"
                    ></v-text-field>
                </v-flex>
            </v-layout>
            <v-layout row>
                <v-flex xs12>
                    <v-data-table
                        :headers="headers"
                        :items="song_lyrics"
                        :search="search_string"
                        :custom-filter="customFilter"
                        :rows-per-page-items="[
                            10,
                            25,
                            { text: 'Vše', value: -1 }
                        ]"
                        class="users-list"
                    >
                        <template v-slot:items="props">
                            <td>
                                <a
                                    :href="
                                        '/admin/song/' + props.item.id + '/edit'
                                    "
                                    >{{ props.item.name }}</a
                                >
                            </td>
                            <td>
                                <span v-if="props.item.type === 0"
                                    >Originál</span
                                >
                                <span v-if="props.item.type === 1"
                                    >Překlad</span
                                >
                                <span v-if="props.item.type === 2"
                                    >Autorizovaný překlad</span
                                >
                                <span v-if="props.item.is_arrangement === true">
                                    Aranž<br />{{
                                        props.item.arrangement_source.name
                                    }}
                                </span>
                            </td>
                            <td>
                                {{
                                    props.item.authors
                                        .map(a => a.name)
                                        .join(', ') ||
                                        (props.item.has_anonymous_author
                                            ? '(anonymní)'
                                            : '-')
                                }}
                            </td>
                            <td>{{ props.item.updated_at }}</td>
                            <td>
                                <span v-if="props.item.is_published">Ano</span>
                                <span v-else>Ne</span>
                            </td>
                            <td>
                                <span v-if="props.item.only_regenschori"
                                    >jen R</span
                                >
                                <span v-else>R + PS</span>
                            </td>
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
    query FetchSongLyrics(
        $has_lyrics: Boolean
        $has_authors: Boolean
        $has_chords: Boolean
        $has_tags: Boolean
    ) {
        song_lyrics(
            has_lyrics: $has_lyrics
            has_authors: $has_authors
            has_chords: $has_chords
            has_tags: $has_tags
        ) {
            id
            name
            updated_at
            type
            is_published
            authors {
                name
            }
            only_regenschori
            has_anonymous_author
            is_arrangement
            arrangement_source {
                name
            }
        }
    }
`;

const delete_item = gql`
    mutation DeleteSongLyric($id: ID!) {
        delete_song_lyric(id: $id) {
            id
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
                { text: 'Název písničky', value: 'name' },
                { text: 'Typ', value: 'type' },
                { text: 'Autoři', value: 'only_regenschori' },
                { text: 'Naposledy upraveno', value: 'updated_at' },
                { text: 'Publikováno', value: 'is_published' },
                { text: 'Zveřejnění', value: 'only_regenschori' },
                { text: 'Akce', value: 'action' }
            ],
            search_string: '',
            filter_mode: 'no-filter'
        };
    },

    apollo: {
        song_lyrics: {
            query: fetch_items,
            variables() {
                return {
                    has_lyrics:
                        this.filter_mode == 'no-lyrics' ? false : undefined,
                    has_authors:
                        this.filter_mode == 'no-authors' ? false : undefined,
                    has_chords:
                        this.filter_mode == 'no-chords' ? false : undefined,
                    has_tags: this.filter_mode == 'no-tags' ? false : undefined
                };
            },
            result(result) {
                this.buildSearchIndex();
            }
        }
    },

    methods: {
        askForm(id) {
            if (confirm('Opravdu chcete smazat daný záznam?')) {
                this.deleteSong(id);
            }
        },

        deleteSong(id) {
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
            for (var item of this.song_lyrics) {
                const types = ['original', 'preklad', 'autorizovany preklad'];

                let searchableItems = [
                    item.name,
                    item.authors.map(a => a.name).join(' ') ||
                        (item.has_anonymous_author ? 'anonymni' : ''), // authors
                    types[item.type]
                ];

                if (item.is_arrangement) {
                    searchableItems.push('aranz');
                    searchableItems.push(item.arrangement_source.name);
                }

                const str = removeDiacritics(
                    searchableItems.join(' ')
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
