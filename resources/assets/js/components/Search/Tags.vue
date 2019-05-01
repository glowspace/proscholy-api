<template>
    <div>
        <div class="song-tags">
            <a
                 v-bind:class="['tag', 'tag-blue', tag.selected ? 'tag-selected' : '']"
                 v-for="tag in tags_official" v-bind:key="tag.id"
                 v-on:click="selectTag(tag)"
            >
                {{ tag.name }}
            </a>
        </div>

        <div class="song-tags">
            <span v-for="tag in tags_unofficial" v-bind:key="tag.id">
                <a v-bind:class="['tag', 'tag-green', tag.selected ? 'tag-selected' : '']" v-on:click="selectTag(tag)">
                    {{ tag.name }}
                </a>

                <a 
                    v-bind:class="['tag', 'tag-yellow', child_tag.selected ? 'tag-selected' : '']"
                    v-for="child_tag in tag.child_tags" v-bind:key="child_tag.id" 
                    v-on:click="selectTag(child_tag)"
                >
                    {{ child_tag.name }}
                </a>
            </span>
        </div>
    </div>
</template>

<style>
    .tag.tag-selected {
        font-weight: bold;
    }
</style>


<script>
import { store } from "./store.js";
import gql from 'graphql-tag';

const fetch_items = gql`
        query FetchSongLyrics {
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
    props: [],

    data() {
        return {
            store: store,
            // custom data here
            // abc: ""
        }
    },

    apollo: {
        tags: { 
            query: fetch_items,
            result(obj){
                // the obj property is immutable, so create a deep copy to enable manipulation and v-model
                console.log(obj);
                this.store.tagsData = _.cloneDeep(obj.data.tags);
            }
        }
    },

    computed: {
        tags_official() {
            if (this.store.tagsData)
                return this.store.tagsData.filter(tag => tag.type == 1);
        },

        tags_unofficial() {
            if (this.store.tagsData)
                return this.store.tagsData.filter(tag => 

                tag.parent_tag == null &&
                tag.type == 0
            );
        }
    },

    methods: {
        selectTag(tag) {
            console.log("tag clicked")
            // tag.selected = !tag.selected;
            Vue.set(tag, 'selected', !tag.selected);
            // vm.$forceUpdate();
            console.log(tag.selected);
        }
    }
}
</script>

