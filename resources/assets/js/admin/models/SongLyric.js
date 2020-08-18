import gql from 'graphql-tag';
import { belongsToManyMutator, belongsToMutator } from './relations';

const fragment = gql`
    fragment SongLyricFillableFragment on SongLyric {
        id
        name
        authors_pivot {
            id
            authorship_type
            author {
                id
                name
            }
        }
        has_anonymous_author
        lang
        only_regenschori
        lyrics
        lilypond
        song {
            id
            song_lyrics {
                id
                name
                type
            }
        }
        songbook_records {
            id
            number
            songbook {
                id
                name
            }
        }

        tags_liturgy_period {
            id
            name
        }

        tags_liturgy_part {
            id
            name
        }

        tags_history_period {
            id
            name
        }

        tags_generic {
            id
            name
        }

        tags_saints {
            id
            name
        }

        tags_musical_form {
            id
            name
        }

        capo
        liturgy_approval_status

        arrangement_source {
            id
            name
        }
    }
`;

const QUERY = gql`
    query($id: ID!) {
        model_database: song_lyric(id: $id) {
            ...SongLyricFillableFragment

            public_url

            lang_string_values
            liturgy_approval_status_string_values
            authorship_type_string_values

            is_arrangement

            externals {
                id
                public_name
                url
                type
            }
            files {
                id
                public_name
                url
                type
            }

            arrangements {
                id
                name
                externals {
                    id
                    public_name
                    url
                    type
                }
                files {
                    id
                    public_name
                    url
                    type
                }
                authors {
                    name
                }
            }
        }
    }
    ${fragment}
`;

const MUTATION = gql`
    mutation(
        $input: UpdateSongLyricInput!
        $liturgyPartTagsInput: SyncCreateTagsRelation!
        $genericTagsInput: SyncCreateTagsRelation!
        $historyPeriodTagsInput: SyncCreateTagsRelation!
        $liturgyPeriodTagsInput: SyncCreateTagsRelation!
        $saintsTagsInput: SyncCreateTagsRelation!
        $musicalFormTagsInput: SyncCreateTagsRelation!
        $taggable_id: Int!
    ) {
        sync_tags_liturgy_part: sync_create_tags(
            input: $liturgyPartTagsInput
            tags_type: LITURGY_PART
            taggable: SONG_LYRIC
            taggable_id: $taggable_id
        ) {
            id
            name
        }

        sync_tags_generic: sync_create_tags(
            input: $genericTagsInput
            tags_type: GENERIC
            taggable: SONG_LYRIC
            taggable_id: $taggable_id
        ) {
            id
            name
        }

        sync_tags_history_period: sync_create_tags(
            input: $historyPeriodTagsInput
            tags_type: HISTORY_PERIOD
            taggable: SONG_LYRIC
            taggable_id: $taggable_id
        ) {
            id
            name
        }

        sync_tags_liturgy_period: sync_create_tags(
            input: $liturgyPeriodTagsInput
            tags_type: LITURGY_PERIOD
            taggable: SONG_LYRIC
            taggable_id: $taggable_id
        ) {
            id
            name
        }

        sync_tags_saints: sync_create_tags(
            input: $saintsTagsInput
            tags_type: SAINTS
            taggable: SONG_LYRIC
            taggable_id: $taggable_id
        ) {
            id
            name
        }

        sync_tags_musical_form: sync_create_tags(
            input: $musicalFormTagsInput
            tags_type: MUSICAL_FORM
            taggable: SONG_LYRIC
            taggable_id: $taggable_id
        ) {
            id
            name
        }

        update_song_lyric(input: $input) {
            ...SongLyricFillableFragment
        }
    }
    ${fragment}
`;

export default {
    fragment,
    QUERY,
    MUTATION,

    getQueryVariables: vueModel => ({
        id: vueModel.id
    }),

    getMutationVariables: vueModel => ({
        input: {
            id: vueModel.id,
            name: vueModel.name,
            lang: vueModel.lang,
            has_anonymous_author: vueModel.has_anonymous_author,
            only_regenschori: vueModel.only_regenschori,
            lyrics: vueModel.lyrics,
            lilypond: vueModel.lilypond,
            song: vueModel.song,
            capo: vueModel.capo,
            liturgy_approval_status: vueModel.liturgy_approval_status,
            // a pivot mutator
            authors: {
                sync: vueModel.authors_pivot
                    .filter(a => a.author && a.author.hasOwnProperty('id'))
                    .map(a => ({
                        author_id: parseInt(a.author.id),
                        authorship_type: a.authorship_type
                    })),
                create: vueModel.authors_pivot
                    .filter(a => a.author && !a.author.hasOwnProperty('id'))
                    .map(a => ({
                        author_name: a.author.name,
                        authorship_type: a.authorship_type
                    }))
            },
            arrangement_source:
                vueModel.arrangement_source === null
                    ? null
                    : belongsToMutator(vueModel.arrangement_source),

            // a pivot mutator
            songbook_records: {
                sync: vueModel.songbook_records.map(m => ({
                    songbook_id: parseInt(m.songbook.id),
                    number: m.number
                }))
            }
        },
        liturgyPartTagsInput: belongsToManyMutator(vueModel.tags_liturgy_part, {
            disableCreate: true
        }),
        liturgyPeriodTagsInput: belongsToManyMutator(
            vueModel.tags_liturgy_period,
            {
                disableCreate: true
            }
        ),
        historyPeriodTagsInput: belongsToManyMutator(
            vueModel.tags_history_period,
            {
                disableCreate: true
            }
        ),
        genericTagsInput: belongsToManyMutator(vueModel.tags_generic),
        saintsTagsInput: belongsToManyMutator(vueModel.tags_saints),
        musicalFormTagsInput: belongsToManyMutator(vueModel.tags_musical_form, {
            disableCreate: true
        }),
        taggable_id: vueModel.id
    })
};
