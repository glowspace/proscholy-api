import gql from "graphql-tag";
import { belongsToManyMutator, belongsToMutator } from "./relations";

const fragment = gql`
    fragment SongLyricFillableFragment on SongLyric {
        id
        name
        authors {
            id
            name
        }
        has_anonymous_author
        lang
        only_regenschori
        lyrics
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
    mutation($input: UpdateSongLyricInput!,
            $liturgyPartTagsInput: SyncCreateTagsRelation!,
            $genericTagsInput: SyncCreateTagsRelation!,
            $historyPeriodTagsInput: SyncCreateTagsRelation!, 
            $liturgyPeriodTagsInput: SyncCreateTagsRelation!, 
            $saintsTagsInput: SyncCreateTagsRelation!, 
            $taggable_id: Int!) {

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
            song: vueModel.song,
            capo: vueModel.capo,
            liturgy_approval_status: vueModel.liturgy_approval_status,
            authors: belongsToManyMutator(vueModel.authors),
            arrangement_source: vueModel.arrangement_source === null ? null : belongsToMutator(vueModel.arrangement_source),

            // specific mutator
            songbook_records: {
                // //was not working
                // //create: vueModel.songbook_records.filter(m => typeof m.songbook === "string"),
                sync: vueModel.songbook_records.map(m => ({
                    songbook_id: parseInt(m.songbook.id),
                    number: m.number
                }))
            }
        },
        liturgyPartTagsInput: belongsToManyMutator(vueModel.tags_liturgy_part, {
            disableCreate: true
        }),
        liturgyPeriodTagsInput: belongsToManyMutator(vueModel.tags_liturgy_period, {
            disableCreate: true
        }),
        historyPeriodTagsInput: belongsToManyMutator(vueModel.tags_history_period, {
            disableCreate: true
        }),
        genericTagsInput: belongsToManyMutator(vueModel.tags_generic),
        saintsTagsInput: belongsToManyMutator(vueModel.tags_saints),
        taggable_id: vueModel.id
    })
};
