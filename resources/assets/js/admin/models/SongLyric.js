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
        tags_unofficial: tags(type: 0) {
            id
            name
        }
        tags_official: tags(type: 1) {
            id
            name
        }
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
        capo
        liturgy_approval_status
    }
`;

const QUERY = gql`
    query($id: ID!) {
        model_database: song_lyric(id: $id) {
            ...SongLyricFillableFragment

            public_url

            lang_string_values
            liturgy_approval_status_string_values

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
        }
    }
    ${fragment}
`;

const MUTATION = gql`
    mutation($input: UpdateSongLyricInput!) {
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
            tags_unofficial: belongsToManyMutator(vueModel.tags_unofficial),
            tags_official: belongsToManyMutator(vueModel.tags_official, {
                disableCreate: true
            }),

            // specific mutator
            songbook_records: {
                // //was not working
                // //create: vueModel.songbook_records.filter(m => typeof m.songbook === "string"),
                sync: vueModel.songbook_records.map(m => ({
                    songbook_id: parseInt(m.songbook.id),
                    number: m.number
                }))
            }
        }
    })
};
