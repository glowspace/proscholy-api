<template>
    <!-- v-app must wrap all the components -->
    <v-app :dark="$root.dark">
        <notifications />
        <v-container fluid grid-list-xs>
            <h1>Písně</h1>
            <create-model
                v-model="search_string"
                class-name="SongLyric"
                label="Název písně"
                success-msg="Píseň úspěšně vytvořena"
                @saved="$apollo.queries.song_lyrics.refetch()"
            ></create-model>
            <v-layout row wrap>
                <v-radio-group v-model="filter_mode" row>
                    <v-radio label="Všechny písně" value="no-filter"></v-radio>
                    <v-radio label="Bez textu" value="no-lyrics"></v-radio>
                    <v-radio label="Bez akordů" value="no-chords"></v-radio>
                    <v-radio label="Bez autora" value="no-author"></v-radio>
                    <v-radio label="Bez štítků" value="no-tags"></v-radio>
                    <v-radio label="Bez licence" value="no-license"></v-radio>
                    <v-radio label="Bez not" value="no-scores"></v-radio>
                    <v-radio
                        label="Bez LP not"
                        value="needs-lilypond"
                    ></v-radio>
                    <v-radio
                        label="LP noty k aktualizaci"
                        value="needs-lilypond-update"
                    ></v-radio>
                </v-radio-group>
            </v-layout>

            <v-layout row v-if="filter_mode == 'no-license'">
                <v-flex xs12>
                    <div class="card">
                        <div class="card-header h5">Doplňování licencí</div>
                        <div class="card-body">
                            Zobrazeny všechny písně bez uvedené licence. K
                            takovým je potřeba získat čestné prohlášení Creative
                            Commons od autora.
                        </div>
                    </div>
                </v-flex>
            </v-layout>

            <v-layout row v-if="filter_mode == 'no-scores'">
                <v-flex xs12>
                    <div class="card">
                        <div class="card-header h5">Doplňování not</div>
                        <div class="card-body">
                            Zobrazeny všechny písně, ke kterým je potřeba dodat
                            noty.<br />
                            <b>Upozornění:</b> U písní bez licence lze přidávat
                            pouze externí noty (odkaz)! K písním bez licence
                            zatím nenahávejte LilyPond ani soubory.
                        </div>
                    </div>
                </v-flex>
            </v-layout>

            <v-layout row v-if="filter_mode == 'needs-lilypond'">
                <v-flex xs12>
                    <div class="card">
                        <div class="card-header h5">
                            Doplňování LilyPond notových sazeb
                        </div>
                        <div class="card-body">
                            LilyPond lze doplnit pouze k písním s přidanou
                            licencí.<br />
                            Písně, ke kterým je potřeba dosázet LilyPond noty a
                            zároveň mají vyřešenou licenci, jsou vypsané níže.
                        </div>
                    </div>
                </v-flex>
            </v-layout>

            <v-layout row>
                <v-flex xs12>
                    <v-card>
                        <v-data-table
                            :headers="headers"
                            :items="song_lyrics"
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
                                            '/admin/song/' +
                                                props.item.id +
                                                '/edit'
                                        "
                                    >
                                        <song-name :song="props.item" />
                                    </a>
                                </td>
                                <td>
                                    <span v-if="props.item.type === 0"
                                        >Orig.</span
                                    >
                                    <span v-if="props.item.type === 1"
                                        >Překl.</span
                                    >
                                    <span v-if="props.item.type === 2"
                                        >Aut.&nbsp;p.</span
                                    >
                                    <span
                                        v-if="
                                            props.item.is_arrangement === true
                                        "
                                    >
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
                                                : '–')
                                    }}
                                </td>
                                <td>
                                    {{
                                        new Date(
                                            props.item.updated_at
                                        ).toLocaleString()
                                    }}
                                </td>
                                <td>
                                    <span>{{
                                        props.item.visit_info.count_total
                                    }}</span>
                                </td>
                                <td>
                                    <span>{{
                                        props.item.visit_info.count_week
                                    }}</span>
                                </td>
                                <td>
                                    <span v-if="props.item.is_sealed">✓</span>
                                </td>
                                <td class="text-nowrap">
                                    <a
                                        class="text-secondary mr-3"
                                        :href="
                                            '/admin/song/' +
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

import removeDiacritics from 'Admin/helpers/removeDiacritics';
import CreateModel from 'Admin/components/CreateModel.vue';
import SongName from '@bit/proscholy.utilities.song-name/SongName.vue';

const fetch_items = gql`
    query FetchSongLyrics(
        $has_lyrics: Boolean
        $has_authors: Boolean
        $has_chords: Boolean
        $has_tags: Boolean
        $has_license: Boolean
        $has_scores: Boolean
        $needs_lilypond: Boolean
        $needs_lilypond_update: Boolean
    ) {
        song_lyrics(
            has_lyrics: $has_lyrics
            has_authors: $has_authors
            has_chords: $has_chords
            has_tags: $has_tags
            has_license: $has_license
            has_scores: $has_scores
            needs_lilypond: $needs_lilypond
            needs_lilypond_update: $needs_lilypond_update
        ) {
            id
            name
            secondary_name_1
            secondary_name_2
            updated_at
            visit_info {
                count_total
                count_week
            }
            type
            authors {
                name
            }
            is_sealed
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

const stringToSearchString = string => {
    return removeDiacritics(string)
        .toLowerCase()
        .replace(/[^a-zA-Z0-9 ]/g, '');
};

export default {
    components: {
        CreateModel,
        SongName
    },

    data() {
        return {
            headers: [
                { text: 'Název písničky', value: 'name' },
                { text: 'Typ', value: 'type' },
                { text: 'Autoři', value: 'authors', sortable: false },
                { text: 'Naposledy upraveno', value: 'updated_at' },
                { text: 'Zobrazení', value: 'visit_info.count_total' },
                { text: 'Zobrazení (týden)', value: 'visit_info.count_week' },
                { text: 'Pečeť', value: 'is_sealed' },
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
        song_lyrics: {
            query: fetch_items,
            variables() {
                return {
                    has_lyrics:
                        this.filter_mode == 'no-lyrics' ? false : undefined,
                    has_authors:
                        this.filter_mode == 'no-author' ? false : undefined,
                    has_chords:
                        this.filter_mode == 'no-chords' ? false : undefined,
                    has_tags: this.filter_mode == 'no-tags' ? false : undefined,
                    has_license:
                        this.filter_mode == 'no-license' ? false : undefined,
                    has_scores:
                        this.filter_mode == 'no-scores' ? false : undefined,
                    needs_lilypond:
                        this.filter_mode == 'needs-lilypond' ? true : undefined,
                    needs_lilypond_update:
                        this.filter_mode == 'needs-lilypond-update'
                            ? true
                            : undefined
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
                    item.secondary_name_1,
                    item.secondary_name_2,
                    item.authors.map(a => a.name).join(' ') ||
                        (item.has_anonymous_author ? 'anonymni' : ''), // authors
                    types[item.type]
                ];

                if (item.is_arrangement) {
                    searchableItems.push('aranz');
                    searchableItems.push(item.arrangement_source.name);
                }

                const str = stringToSearchString(searchableItems.join(' '));

                this.$set(item, 'search_index', str);
            }
        },

        customFilter(items, search) {
            const needle = stringToSearchString(search);

            return items.filter(
                item => item.search_index.indexOf(needle) !== -1
            );
        }
    }
};
</script>
