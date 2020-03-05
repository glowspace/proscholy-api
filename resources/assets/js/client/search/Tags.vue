<template>
    <div>
        <div v-bind:class="['song-tags', is_filtered ? 'filtered' : '']">
            <a v-bind:class="['tag', 'tag-blue', isSelected(tag) ? 'tag-selected' : '']"
               v-for="tag in tags_official"
               v-bind:key="tag.id"
               v-on:click="selectTag(tag)">
                {{ tag.name }}
            </a>

        </div>

        <div v-bind:class="['song-tags', is_filtered ? 'filtered' : '']">
            <span v-for="tag in tags_unofficial"
                  v-bind:key="tag.id">
                <a v-bind:class="['tag', 'tag-green', isSelected(tag) ? 'tag-selected' : '']"
                   v-on:click="selectTag(tag)">
                    {{ tag.name }}
                </a>

                <a v-bind:class="['tag', 'tag-yellow', isSelected(child_tag) ? 'tag-selected' : '']"
                   v-for="child_tag in tag.child_tags"
                   v-bind:key="child_tag.id"
                   v-on:click="selectTag(child_tag)">
                    {{ child_tag.name }}
                </a>

            </span>
        </div>
    </div>
</template>

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


<script>
    import gql from 'graphql-tag';

    const fetch_items = gql`
        query FetchTags {
            tags {
                id,
                name,
                type,
                child_tags {
                    id,
                    name
                },
                parent_tag {id}
            }
        }`;


    export default {
        // type: {"tag_id": true, "tag_id2": true}
        props: ["value"],

        data() {
            return {
                selectedTags: { }
            }
        },

        apollo: {
            tags: {
                query: fetch_items
            }
        },

        mounted(){
            // bind selected tags to the preselected value, originally from GET request
            this.selectedTags = this.value;
        },

        computed: {
            tags_official() {
                if (this.tags) {
                    return this.tags.filter(tag => tag.type == 1);
                }
            },

            tags_unofficial() {
                if (this.tags) {
                    return this.tags.filter(tag =>

                        tag.parent_tag == null &&
                        tag.type == 0
                    );
                }
            },

            is_filtered() {
                return Object.keys(this.selectedTags).length > 0;
            }
        },

        methods: {
            selectTag(tag) {
                if (!this.selectedTags[tag.id])
                {
                    Vue.set(this.selectedTags, tag.id, true);
                }
                else 
                {
                    Vue.delete(this.selectedTags, tag.id);
                }

                // notify the parent that sth has changed
                // works with v-model
                this.$emit('input', this.selectedTags);
            },

            isSelected(tag) {
                return this.selectedTags[tag.id];
            }
        }
    }
</script>

