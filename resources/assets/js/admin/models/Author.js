import gql from "graphql-tag";
import { belongsToManyMutator } from './relations';

const fragment = gql`
        fragment AuthorFillableFragment on Author {
            id
            name
            type
            description
            members {
                id
                name
            }
            memberships {
                id
                name
            }
            song_lyrics {
                id
                name
            }
            externals {
                id
                url
                public_name
            }
            files {
                id
                public_name
            }
            tags_period {
                id
                name
            }
        }
    `;

const QUERY = gql`
        query($id: ID!) {
            model_database: author(id: $id) {
                ...AuthorFillableFragment

                type_string_values
            }
        }
        ${fragment}
    `;

const MUTATION = gql`
        mutation($input: UpdateAuthorInput!
            $periodTagsInput: SyncCreateTagsRelation!
            $taggable_id: Int!
        ) {
            sync_tags_period: sync_create_tags(
                input: $periodTagsInput
                tags_type: 10
                taggable: AUTHOR
                taggable_id: $taggable_id
            ) {
                id
                name
            }
            update_author(input: $input) {
                ...AuthorFillableFragment
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
            type: vueModel.type,
            description: vueModel.description,
            members: belongsToManyMutator(vueModel.members)
        },
        periodTagsInput: belongsToManyMutator(vueModel.tags_period),
        taggable_id: vueModel.id
    })
}
