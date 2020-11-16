<template>
    <!-- v-app must wrap all the components -->
    <v-app :dark="$root.dark">
        <notifications />
        <h1 class="h2 mb-3">Štítky</h1>

        <template v-for="(tag_group, i) in tag_groups">
            <h2 :key="i">
                {{ tag_group.name }}
                <i v-if="tag_group.regenschori"> (regenschori)</i>
            </h2>
            <TagsListGroup :key="i" :type-enum="tag_group.enum" />
        </template>
    </v-app>
</template>

<script>
import gql from 'graphql-tag';

import TagsListGroup from './TagsListGroup.vue';

export default {
    components: {
        TagsListGroup
    },

    data() {
        return {
            tag_groups: [
                {
                    name: 'obecné',
                    enum: 'GENERIC'
                },
                {
                    name: 'část liturgie',
                    enum: 'LITURGY_PART'
                },
                {
                    name: 'liturgické období',
                    enum: 'LITURGY_PERIOD'
                },
                {
                    name: 'svatí',
                    enum: 'SAINTS'
                },
                {
                    name: 'historické období',
                    enum: 'HISTORY_PERIOD',
                    regenschori: true
                },
                {
                    name: 'instrumentace',
                    enum: 'INSTRUMENTATION',
                    regenschori: true
                },
                {
                    name: 'žánr',
                    enum: 'GENRE',
                    regenschori: true
                },
                {
                    name: 'hudební forma',
                    enum: 'MUSICAL_FORM',
                    regenschori: true
                }
            ]
        };
    }
};
</script>
