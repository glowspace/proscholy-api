<template>
    <div>
        <div v-bind:class="['song-tags']">

            <h3>Liturgie - mše svatá</h3>

            <a v-bind:class="['tag', 'tag-blue', isSelectedTag(tag) ? 'tag-selected' : '']"
               v-for="tag in tags_official"
               v-bind:key="tag.id"
               v-on:click="selectTag(tag)">
                {{ tag.name }}
            </a>


            <div v-for="tag in parent_tags_unofficial"
                  v-bind:key="tag.id">
                <h3>{{ tag.description }}</h3>

                <a v-bind:class="['tag', 'tag-green', isSelectedTag(child_tag) ? 'tag-selected' : '']"
                   v-for="child_tag in tag.child_tags"
                   v-bind:key="child_tag.id"
                   v-on:click="selectTag(child_tag)">
                    {{ child_tag.name }}
                </a>

            </div>

            <h3>Zpěvníky</h3>

            <a v-bind:class="['tag', 'tag-yellow', isSelectedSongbook(songbook) ? 'tag-selected' : '']"
               v-for="songbook in songbooks"
               v-bind:key="songbook.id"
               v-on:click="selectSongbook(songbook)"
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
    props: ['selected-tags', 'selected-songbooks'],

    data() {
        return {
            selected_tags: {},
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
        selectTag(tag) {
            if (!this.selected_tags[tag.id])
            {
                Vue.set(this.selected_tags, tag.id, true);
            }
            else 
            {
                Vue.delete(this.selected_tags, tag.id);
            }

            // notify the parent that sth has changed
            this.$emit('update:selected-tags', this.selected_tags);
        },

        selectSongbook(songbook) {
            if (!this.selected_songbooks[songbook.id])
            {
                Vue.set(this.selected_songbooks, songbook.id, true);
            }
            else 
            {
                Vue.delete(this.selected_songbooks, songbook.id);
            }

            // notify the parent that sth has changed
            this.$emit('update:selected-songbooks', this.selected_songbooks);
        },

        isSelectedTag(tag) {
            return this.selected_tags[tag.id];
        },

        isSelectedSongbook(songbook) {
            return this.selected_songbooks[songbook.id];
        },
    },

    // watch: {
    //     selectedTags(val, prev) {

    //     },

    //     selectedSongbooks(val, prev) {

    //     },
    // }
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