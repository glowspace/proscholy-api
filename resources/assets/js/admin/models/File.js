import gql from "graphql-tag";
import { belongsToManyMutator, belongsToMutator } from "./relations";

const fragment = gql`
    fragment FileFillableFragment on File {
        id
        type
        filename
        name
        authors {
            id
            name
        }
        song_lyric {
            id
            name: rich_name
            public_url
        }
    }
`;

const QUERY = gql`
    query($id: ID!) {
        model_database: file(id: $id) {
            ...FileFillableFragment
            url
            type_string_values
            external_type
        }
    }
    ${fragment}
`;

const MUTATION = gql`
    mutation($input: UpdateFileInput!) {
        update_file(input: $input) {
            ...FileFillableFragment
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
            filename: vueModel.filename,
            type: vueModel.type,
            song_lyric: belongsToMutator(vueModel.song_lyric),
            authors: belongsToManyMutator(vueModel.authors)
        }
    })
};
