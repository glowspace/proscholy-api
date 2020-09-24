import gql from 'graphql-tag';
import { belongsToManyMutator, belongsToMutator } from './relations';

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
        catalog_number
        copyright
        editor
        published_by
        caption
        is_uploaded
        media_type
        content_type
    }
`;

const QUERY = gql`
    query($id: ID!) {
        model_database: external(id: $id) {
            ...ExternalFillableFragment
            type_string_values
            media_type_values
            content_type_string_values
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
            media_type: vueModel.media_type,
            content_type: vueModel.content_type,
            copyright: vueModel.copyright,
            editor: vueModel.editor,
            published_by: vueModel.published_by,
            catalog_number: vueModel.catalog_number,
            caption: vueModel.caption,
            is_uploaded: vueModel.is_uploaded,
            song_lyric: belongsToMutator(vueModel.song_lyric),
            authors: belongsToManyMutator(vueModel.authors)
        },
        instrumentationTagsInput: belongsToManyMutator(
            vueModel.tags_instrumentation
        ),
        taggable_id: vueModel.id
    })
};
