import gql from 'graphql-tag';
import { belongsToManyMutator, belongsToMutator } from './relations';

const lilypond_parts_sheet_music_fragment = gql`
    fragment LilypondPartsSheetMusicFragment on LilypondPartsSheetMusic {
        lilypond_parts {
            name
            src
            key_major
            time_signature
        }
        global_src
        sequence_string
        score_config {
            two_voices_per_staff
            merge_rests
            version
            note_splitting
        }
    }
`;

const fragment = gql`
    fragment SongLyricFillableFragment on SongLyric {
        id
        name
        secondary_name_1
        secondary_name_2
        licence_type_cc
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
        lilypond_key_major
        lilypond_parts_sheet_music {
            ...LilypondPartsSheetMusicFragment
        }
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

        tags_sacred_occasion {
            id
            name
        }

        tags_topic {
            id
            name
        }

        tags_liturgy_day {
            id
            name
        }

        capo
        liturgy_approval_status

        arrangement_source {
            id
            name
        }

        bible_refs_src
        bible_refs_osis

        admin_note
        is_sealed

        is_for_band
        is_for_choir
        is_for_organ
    }
    ${lilypond_parts_sheet_music_fragment}
`;

const external_fragment = gql`
    fragment ExternalFragment on External {
        id
        url
        media_type
        content_type
        authors {
            name
        }
    }
`;

const QUERY = gql`
    query($id: ID!) {
        model_database: song_lyric(id: $id) {
            ...SongLyricFillableFragment

            public_url
            public_route

            lang_string_values
            liturgy_approval_status_string_values
            authorship_type_string_values
            licence_type_cc_string_values
            lilypond_key_major_string_values

            is_arrangement

            externals {
                ...ExternalFragment
            }

            arrangements {
                id
                name
                externals {
                    ...ExternalFragment
                }
                authors {
                    name
                }
            }
        }
    }
    ${fragment}
    ${external_fragment}
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
        $sacredOccasionTagsInput: SyncCreateTagsRelation!
        $topicTagsInput: SyncCreateTagsRelation!
        $liturgyDayTagsInput: SyncCreateTagsRelation!
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

        sync_tags_sacred_occasion: sync_create_tags(
            input: $sacredOccasionTagsInput
            tags_type: SACRED_OCCASION
            taggable: SONG_LYRIC
            taggable_id: $taggable_id
        ) {
            id
            name
        }

        sync_tags_topic: sync_create_tags(
            input: $topicTagsInput
            tags_type: TOPIC
            taggable: SONG_LYRIC
            taggable_id: $taggable_id
        ) {
            id
            name
        }

        sync_tags_liturgy_day: sync_create_tags(
            input: $liturgyDayTagsInput
            tags_type: LITURGY_DAY
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
    external_fragment,
    lilypond_parts_sheet_music_fragment,
    QUERY,
    MUTATION,

    getQueryVariables: vueModel => ({
        id: vueModel.id
    }),

    getMutationVariables: vueModel => ({
        input: {
            id: vueModel.id,
            name: vueModel.name,
            secondary_name_1: vueModel.secondary_name_1,
            secondary_name_2: vueModel.secondary_name_2,
            lang: vueModel.lang,
            has_anonymous_author: vueModel.has_anonymous_author,
            only_regenschori: vueModel.only_regenschori,
            lyrics: vueModel.lyrics,
            lilypond: vueModel.lilypond,
            // lilypond_svg: vueModel.lilypond_svg,
            lilypond_key_major: vueModel.lilypond_key_major,
            song: {
                id: vueModel.song.id,
                song_lyrics: vueModel.song.song_lyrics.map(sl => ({
                    id: sl.id,
                    name: sl.name,
                    type: sl.type
                }))
            },
            lilypond_parts_sheet_music: vueModel.lilypond_parts_sheet_music,
            capo: vueModel.capo,
            liturgy_approval_status: vueModel.liturgy_approval_status,
            bible_refs_src: vueModel.bible_refs_src,
            bible_refs_osis: vueModel.bible_refs_osis,
            admin_note: vueModel.admin_note,
            licence_type_cc: vueModel.licence_type_cc,
            is_sealed: vueModel.is_sealed,
            is_for_organ: vueModel.is_for_organ,
            is_for_choir: vueModel.is_for_choir,
            is_for_band: vueModel.is_for_band,
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
        sacredOccasionTagsInput: belongsToManyMutator(
            vueModel.tags_sacred_occasion
        ),
        topicTagsInput: belongsToManyMutator(vueModel.tags_topic),
        liturgyDayTagsInput: belongsToManyMutator(vueModel.tags_liturgy_day, {
            disableCreate: true
        }),
        taggable_id: vueModel.id
    })
};

// hello
