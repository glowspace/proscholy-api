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
            name: rich_name
            public_url
        }
        tags_instrumentation {
            id
            name
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
    mutation(
            $input: UpdateExternalInput!
            $instrumentationTagsInput: SyncCreateTagsRelation!
            $taggable_id: Int!
        ) {
        sync_tags_instrumentation: sync_create_tags(
            input: $instrumentationTagsInput
            tags_type: INSTRUMENTATION
            taggable: EXTERNAL
            taggable_id: $taggable_id
        ) {
            id
            name
        }
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
        },
        instrumentationTagsInput: belongsToManyMutator(vueModel.tags_instrumentation),
        taggable_id: vueModel.id
    })
};
