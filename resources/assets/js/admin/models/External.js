import gql from "graphql-tag";
import { belongsToManyMutator, belongsToMutator } from "./relations";

const fragment = gql`
    fragment ExternalFillableFragment on External {
        id
        url
        type
        media_id
        authors {
            id
            name
        }
        song_lyric {
            id
            name
            public_url
        }
    }
`;

const QUERY = gql`
    query($id: ID!) {
        model_database: external(id: $id) {
            ...ExternalFillableFragment
            type_string_values
        }
    }
    ${fragment}
`;

const MUTATION = gql`
    mutation($input: UpdateExternalInput!) {
        update_external(input: $input) {
            ...ExternalFillableFragment
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
            url: vueModel.url,
            type: vueModel.type,
            song_lyric: belongsToMutator(vueModel.song_lyric),
            authors: belongsToManyMutator(vueModel.authors)
        }
    })
};
