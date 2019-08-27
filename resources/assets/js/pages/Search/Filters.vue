<template>
    <div class="song-tags card pt-1">
        <h4>Liturgie – mše svatá</h4>

        <a
            v-bind:class="['tag', 'tag-blue', isSelectedTag(tag) ? 'tag-selected' : '']"
            v-for="tag in tags_official"
            v-bind:key="'tag-'+tag.id"
            v-on:click="selectTag(tag)"
        >{{ tag.name }}</a>

        <div v-for="tag in parent_tags_unofficial" v-bind:key="tag.id">
            <h4>{{ tag.description }}</h4>

            <a
                v-bind:class="['tag', 'tag-green', isSelectedTag(child_tag) ? 'tag-selected' : '']"
                v-for="child_tag in tag.child_tags"
                v-bind:key="'tag-'+child_tag.id"
                v-on:click="selectTag(child_tag)"
            >{{ child_tag.name }}</a>
        </div>

        <h4>Zpěvníky</h4>

        <a
            v-bind:class="['tag', 'tag-yellow', isSelectedSongbook(songbook) ? 'tag-selected' : '']"
            v-for="songbook in songbooks"
            v-bind:key="'songbook-'+songbook.id"
            v-on:click="selectSongbook(songbook)"
        >{{ songbook.name }}</a>

        <h4>Jazyky</h4>

        <a
            v-bind:class="['tag', 'tag-red', isSelectedLanguage(lang_code) ? 'tag-selected' : '']"
            v-for="(lang_name, lang_code) in all_languages"
            v-bind:key="'lang-'+lang_code"
            v-on:click="selectLanguage(lang_code)"
        >{{ lang_name }}</a>
    </div>
</template>

<script>
import gql from "graphql-tag";

const fetch_items = gql`
    query {
        tags {
            id
            name
            type
            child_tags {
                id
                name
            }
            parent_tag {
                id
            }
            description
        }
    }
`;

const fetch_songbooks = gql`
    query {
        songbooks(is_private: false) {
            id
            name
            shortcut
        }
    }
`;

export default {
    props: ["selected-tags", "selected-songbooks", "selected-languages"],

    data() {
        return {
            selected_tags: {},
            selected_songbooks: {},
            selected_languages: {},
            all_languages: {
                cs: "čeština",
                sk: "slovenština",
                en: "angličtina",
                la: "latina",
                pl: "polština",
                de: "němčina",
                fr: "francouzština",
                es: "španělština",
                it: "italština",
                sv: "svahilština",
                he: "hebrejština",
                cu: "staroslověnština",
                mixed: "vícejazyčná píseň"
            }
        };
    },

    apollo: {
        tags: {
            query: fetch_items,
            result() {
                this.$emit('tags-loaded', null);
            }
        },

        songbooks: {
            query: fetch_songbooks
        }
    },

    computed: {
        parent_tags_unofficial() {
            if (this.tags) {
                return this.tags.filter(
                    tag => tag.type == 0 && tag.child_tags.length > 0
                );
            }
        },

        tags_official() {
            if (this.tags) {
                return this.tags.filter(tag => tag.type == 1);
            }
        }
    },

    methods: {
        selectTag(tag) {
            if (!this.isSelectedTag(tag)) {
                Vue.set(this.selected_tags, tag.id, true);
            } else {
                Vue.delete(this.selected_tags, tag.id);
            }

            // notify the parent that sth has changed
            this.$emit("update:selected-tags", this.selected_tags);
            this.$emit("input", null);
        },

        selectSongbook(songbook) {
            if (!this.isSelectedSongbook(songbook)) {
                Vue.set(this.selected_songbooks, songbook.id, true);
            } else {
                Vue.delete(this.selected_songbooks, songbook.id);
            }

            // notify the parent that sth has changed
            this.$emit("update:selected-songbooks", this.selected_songbooks);
            this.$emit("input", null);
        },

        selectLanguage(language) {
            if (!this.isSelectedLanguage(language)) {
                Vue.set(this.selected_languages, language, true);
            } else {
                Vue.delete(this.selected_languages, language);
            }

            // notify the parent that sth has changed
            this.$emit("update:selected-languages", this.selected_languages);
            this.$emit("input", null);
        },

        isSelectedTag(tag) {
            return this.selected_tags[tag.id];
        },

        isSelectedSongbook(songbook) {
            return this.selected_songbooks[songbook.id];
        },

        isSelectedLanguage(language) {
            return this.selected_languages[language];
        },

        getSelectedTagsDcnf() {
            let res = {};

            res["officials"] = this.tags_official
                .filter(tag => this.isSelectedTag(tag))
                .map(tag => tag.id);

            for (parent of this.parent_tags_unofficial) {
                res[parent.id] = parent.child_tags
                    .filter(tag => this.isSelectedTag(tag))
                    .map(tag => tag.id);
            }

            return res;
        },
    },

    watch: {
        // watch props for changes
        selectedTags(val, prev) {
            this.selected_tags = val;

            // ok this needs to be here because otherwise the applyStateChange method on Search.vue
            // doesn't work properly when updating only the selectedTags property
            this.$emit("update:selected-tags-dcnf", this.getSelectedTagsDcnf());
        },

        selectedSongbooks(val, prev) {
            console.log("ahoj");
            this.selected_songbooks = val;
        },

        selectedLanguages(val, prev) {
            this.selected_languages = val;
        }
    }
};
</script>