<template>
    <!-- v-app must wrap all the components -->
    <v-app :dark="$root.dark">
        <notifications />
        <div class="content-header content-header--bordered">
            <h1>Štítky</h1>
        </div>

        <div v-if="tag">
            <template v-for="(tag_group, i) in tag.groups_info">
                <h2 :key="i">
                    {{ tag_group.name }}
                    <i v-if="tag_group.is_regenschori"> (regenschori)</i>
                </h2>
                <TagsListGroup
                    :key="i + 100"
                    :type-enum="tag_group.type"
                    :allow-create="tag_group.is_editable"
                />
            </template>
        </div>
    </v-app>
</template>

<script>
import gql from 'graphql-tag';

import TagsListGroup from './TagsListGroup.vue';

const FETCH_GROUPS = gql`
    query {
        tag(id: 1) {
            groups_info {
                name
                type
                is_editable
                is_regenschori
            }
        }
    }
`;

export default {
    components: {
        TagsListGroup
    },

    apollo: {
        tag: {
            query: FETCH_GROUPS
        }
    }
};
</script>
