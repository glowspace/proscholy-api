import gql from 'graphql-tag';

const fragment = gql`
    fragment NewsItemFillableFragment on NewsItem {
        id
        text
        fa_icon
        link
        link_type
        starts_at
        expires_at
        is_published
    }
`;

const QUERY = gql`
    query($id: ID!) {
        model_database: news_item(id: $id) {
            ...NewsItemFillableFragment
            link_type_string_values
        }
    }
    ${fragment}
`;

const MUTATION = gql`
    mutation($input: UpdateNewsItemInput!) {
        update_news_item(input: $input) {
            ...NewsItemFillableFragment
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
            text: vueModel.text,
            fa_icon: vueModel.fa_icon,
            link: vueModel.link,
            link_type: vueModel.link_type,
            starts_at: vueModel.starts_at + ' 00:00:00',
            expires_at: vueModel.expires_at + ' 23:59:59',
            is_published: vueModel.is_published
        }
    })
};
