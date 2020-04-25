import gql from "graphql-tag";
import { belongsToManyMutator } from "./relations";

const fragment = gql`
    fragment SongbookFillableFragment on Songbook {
        id
        name
        shortcut
        records {
            number
            song_lyric {
                id
                name: rich_name
            }
        }
        songs_count
        is_private
        color
    }
`;

const QUERY = gql`
    query($id: ID!) {
        model_database: songbook(id: $id) {
            ...SongbookFillableFragment
        }
    }
    ${fragment}
`;

const MUTATION = gql`
    mutation($input: UpdateSongbookInput!) {
        update_songbook(input: $input) {
            ...SongbookFillableFragment
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
            shortcut: vueModel.shortcut,
            songs_count: vueModel.songs_count,
            is_private: vueModel.is_private,
            color: vueModel.color,
            records: {
                // first let's filter out records that had been assigned a song_lyric but
                // it was then set to null
                sync: vueModel.records
                    .filter(
                        r =>
                            r.song_lyric !== null &&
                            r.song_lyric.hasOwnProperty("id")
                    )
                    .map(m => ({
                        song_lyric_id: parseInt(m.song_lyric.id),
                        number: m.number
                    })),
                create: vueModel.records
                    .filter(
                        r =>
                            r.song_lyric !== null &&
                            !r.song_lyric.hasOwnProperty("id")
                    )
                    .map(m => ({
                        song_lyric_name: m.song_lyric.name,
                        number: m.number
                    }))
            }
        }
    })
};
