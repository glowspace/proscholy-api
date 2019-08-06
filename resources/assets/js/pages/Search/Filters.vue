<template>
    <div>
        <div v-bind:class="['song-tags']">

            <h3>Liturgie - mše svatá</h3>

            <a v-bind:class="['tag', 'tag-blue']"
               v-for="tag in tags_official"
               v-bind:key="tag.id"
               v-on:click="selectTag(tag)">
                {{ tag.name }}
            </a>


            <div v-for="tag in parent_tags_unofficial"
                  v-bind:key="tag.id">
                <h3>{{ tag.name }}</h3>

                <a v-bind:class="['tag', 'tag-yellow']"
                   v-for="child_tag in tag.child_tags"
                   v-bind:key="child_tag.id"
                   v-on:click="selectTag(child_tag)">
                    {{ child_tag.name }}
                </a>

            </div>

            <h3>Zpěvníky</h3>

            <a v-bind:class="['tag', 'tag-blue']"
               v-for="songbook in songbooks"
               v-bind:key="songbook.id"
               >
                {{ songbook.name }}
            </a>
        </div>
    </div>
</template>

<script>
import gql from 'graphql-tag';

const fetch_items = gql`
        query {
            tags {
                id,
                name,
                type,
                child_tags {
                    id,
                    name
                },
                parent_tag {id},
                description
            }
        }`;

const fetch_songbooks = gql`
        query {
            songbooks(is_private: false) {
                id,
                name,
                shortcut
            }
        }`;


export default {
    props: ['value'],

    data() {
        return {
            selected_tags_official: {},
            selected_tags_unofficial_categories: {},
            selected_songbooks: {}
        }
    },

    apollo: {
        tags: {
            query: fetch_items
        },

        songbooks: {
            query: fetch_songbooks
        }
    },

    computed: {
        parent_tags_unofficial() {
            if (this.tags) {
                return this.tags.filter(tag =>
                    tag.type == 0 && 
                    tag.child_tags.length > 0
                );
            }
        },

        tags_official() {
            if (this.tags) {
                return this.tags.filter(tag => tag.type == 1);
            }
        },
    },

    methods: {
        selectTag(tag, category) {
            var group = this.getCategoryObject(category);

            if (!this.isSelected(tag))
            {
                Vue.set(group, tag.id, true);
            }
            else 
            {
                Vue.delete(this.selectedTags, tag.id);
            }

            // notify the parent that sth has changed
            // works with v-model

            // ok now we need to transform the data a bit

            var selected_tags = {}

            this.$emit('input');
        },

        isSelected(tag, category) {
            return this.getCategoryObject(category)[tag.id];
        },

        getCategoryObject(category) {
            if (category === 'official') {
                return this.selected_tags_official;
                // Vue.set(this.selected_tags_official, tag.id, true);
            } else if (category === 'songbook') {
                return this.selected_songbooks;
            } else {
                // unofficials
                // category is the id of parent tag
                return this.selected_tags_unofficial_categories[category];
            }
        }
    }
}
</script>


<style>
    .song-tags .tag.tag-selected {
        font-weight: bold;
        opacity: 1 !important;
    }

    .song-tags.filtered .tag {
        opacity: 0.5;
    }

    a.tag{
        cursor:pointer;
    }
</style>