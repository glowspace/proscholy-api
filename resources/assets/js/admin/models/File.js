import gql from 'graphql-tag';
import { belongsToManyMutator, belongsToMutator } from './relations';

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
        tags_instrumentation {
            id
            name
        }
        catalog_number
        copyright
        editor
        published_by
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
    mutation(
        $input: UpdateFileInput!
        $instrumentationTagsInput: SyncCreateTagsRelation!
        $taggable_id: Int!
    ) {
        sync_tags_instrumentation: sync_create_tags(
            input: $instrumentationTagsInput
            tags_type: INSTRUMENTATION
            taggable: FILE
            taggable_id: $taggable_id
        ) {
            id
            name
        }
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
            copyright: vueModel.copyright,
            editor: vueModel.editor,
            published_by: vueModel.published_by,
            catalog_number: vueModel.catalog_number,
            song_lyric: belongsToMutator(vueModel.song_lyric),
            authors: belongsToManyMutator(vueModel.authors)
        },
        instrumentationTagsInput: belongsToManyMutator(
            vueModel.tags_instrumentation
        ),
        taggable_id: vueModel.id
    })
};
