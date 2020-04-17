import gql from "graphql-tag";
// import { getFieldsFromFragment } from './manipulation';


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
    }
`;

const FETCH_MODEL = gql`
    query($id: ID!) {
        model_database: author(id: $id) {
            ...AuthorFillableFragment
            type_string_values
        }
    }
    ${fragment}
`;

const MUTATE_MODEL = gql`
    mutation($input: UpdateAuthorInput!) {
        update_author(input: $input) {
            ...AuthorFillableFragment
        }
    }
    ${fragment}
`;


export {FETCH_MODEL, MUTATE_MODEL}
