<template>
    <div class="song-tags card pt-1">
        <!-- todo: make component -->
        <h4>Liturgie – mše svatá</h4>
        <a
            v-bind:class="['tag', 'tag-blue', isSelectedTag(tag) ? 'tag-selected' : '']"
            v-for="tag in tags_liturgy_part"
            v-bind:key="'tag-'+tag.id"
            v-on:click="selectTag(tag)"
        >{{ tag.name }}</a>

        <h4>Liturgický rok</h4>
        <a
            v-bind:class="['tag', 'tag-blue', isSelectedTag(tag) ? 'tag-selected' : '']"
            v-for="tag in tags_liturgy_period"
            v-bind:key="'tag-'+tag.id"
            v-on:click="selectTag(tag)"
        >{{ tag.name }}</a>

        <h4>Příležitosti</h4>
        <a
            v-bind:class="['tag', 'tag-green', isSelectedTag(tag) ? 'tag-selected' : '']"
            v-for="tag in tags_generic"
            v-bind:key="'tag-'+tag.id"
            v-on:click="selectTag(tag)"
        >{{ tag.name }}</a>

        <h4>Ke svatým</h4>
        <a
            v-bind:class="['tag', 'tag-green', isSelectedTag(tag) ? 'tag-selected' : '']"
            v-for="tag in tags_saints"
            v-bind:key="'tag-'+tag.id"
            v-on:click="selectTag(tag)"
        >{{ tag.name }}</a>

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

const FETCH_TAGS_GENERIC = gql`
  query {
    tags_generic: tags_enum(type: GENERIC) {
      id
      name
    }
  }
`;
const FETCH_TAGS_LITURGY_PART = gql`
  query {
    tags_liturgy_part: tags_enum(type: LITURGY_PART) {
      id
      name
    }
  }
`;
const FETCH_TAGS_LITURGY_PERIOD = gql`
  query {
    tags_liturgy_period: tags_enum(type: LITURGY_PERIOD) {
      id
      name
    }
  }
`;
const FETCH_TAGS_SAINTS = gql`
  query {
    tags_saints: tags_enum(type: SAINTS) {
      id
      name
    }
}`;

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
        tags_generic: {
            query: FETCH_TAGS_GENERIC,
        },
        tags_liturgy_part: {
            query: FETCH_TAGS_LITURGY_PART
        },
        tags_liturgy_period: {
            query: FETCH_TAGS_LITURGY_PERIOD
        },
        tags_saints: {
            query: FETCH_TAGS_SAINTS
        },
        songbooks: {
            query: fetch_songbooks
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
            const filterMapTags = tags => 
                            tags.filter(tag => this.isSelectedTag(tag))
                                .map(tag => tag.id);

            return ({
                liturgy_part: filterMapTags(this.tags_liturgy_part),
                liturgy_period: filterMapTags(this.tags_liturgy_period),
                generic: filterMapTags(this.tags_generic),
                saints: filterMapTags(this.tags_saints),
            });
        },
    },

    watch: {
        $apollo: {
            loading(val, prev) {
                if (val && !prev) {
                    this.$emit('tags-loaded', null);
                }
            }
        },

        // watch props for changes
        selectedTags(val, prev) {
            this.selected_tags = val;

            // ok this needs to be here because otherwise the applyStateChange method on Search.vue
            // doesn't work properly when updating only the selectedTags property
            this.$emit("update:selected-tags-dcnf", this.getSelectedTagsDcnf());
        },

        selectedSongbooks(val, prev) {
            this.selected_songbooks = val;
        },

        selectedLanguages(val, prev) {
            this.selected_languages = val;
        }
    }
};
</script>