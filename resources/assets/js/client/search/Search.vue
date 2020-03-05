<template>
    <div class="background-home">
        <div class="container">

            <div :class="[init?'home-init':'home-afterinit']">
                <logo/>

                <div class="row fixed-top position-sticky mt-n4 justify-content-center zindex-lower">
                    <div :class="[{'col-lg-6': init}, 'col-lg-8 px-1 pt-5 pb-3 search-column']">
                        <div class="search-wrapper shadow">
                            <input class="search-home"
                                   placeholder="Zadejte název písně, část textu nebo jméno autora"
                                   v-model="search_string"
                                   v-on:keyup.enter="init=false"
                                   autofocus
                            >
                            <button type="button"
                                    class="search-submit"
                                    v-if="init"
                                    @click="init=false">
                                <i class="fa fa-search d-none d-sm-inline"></i>
                            </button>
                            <button type="button"
                                    class="search-submit d-none d-lg-inline"
                                    v-if="!init">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button"
                                    class="search-submit d-lg-none"
                                    v-if="!init"
                                    :class="{'filter-active': filters_active, 'filter-open': displayFilter}"
                                    @click="displayFilter=!displayFilter">
                                <i class="fa fa-filter"></i>
                            </button>
                        </div>
                        <div v-if="init"
                             @click="resetState(true); init=false;"
                             class="text-center pt-4 text-white">
                            <a class="btn btn-outline-light display-all-songs font-weight-bold"><i class="fas fa-chevron-down pr-1"></i>
                                ZOBRAZIT VŠECHNY PÍSNĚ</a></div>
                        <div class="mx-2 d-lg-none filter-panel position-absolute"
                             v-show="!init && displayFilter">
                            <a class="btn btn-secondary float-right fixed-top position-sticky"
                               v-on:click="displayFilter=false">
                                <i class="fas fa-times pr-0"></i>
                            </a>
                            <!-- filters shown only for mobile -->
                            <Filters
                                v-bind:selected-songbooks.sync="selected_songbooks"
                                v-bind:selected-tags.sync="selected_tags"
                                v-bind:selected-languages.sync="selected_languages"
                                v-on:update:selected-tags-dcnf="updateSelectedTagsDcnf($event)"
                                v-on:input="updateHistoryState"
                            ></Filters>
                        </div>
                    </div>
                    <div class="col-lg-4 search-balance"></div>
                </div>
                <div class="row"
                     v-show="!init">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body p-0">
                                <SongsList
                                    v-bind:search-string="search_string"
                                    v-bind:selected-tags-dcnf="selected_tags_dcnf"
                                    v-bind:selected-tags="selected_tags"
                                    v-bind:selected-songbooks="selected_songbooks"
                                    v-bind:selected-languages="selected_languages"
                                    v-bind:init="init"
                                    v-on:query-loaded="autoInit(); updateHistoryState();"
                                ></SongsList>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 d-none d-lg-block desktop-filter-container">
                        <div class="fixed-top position-sticky">
                            <!-- filters shown only for desktop -->
                            <Filters
                                v-bind:selected-songbooks.sync="selected_songbooks"
                                v-bind:selected-tags.sync="selected_tags"
                                v-bind:selected-languages.sync="selected_languages"
                                v-on:update:selected-tags-dcnf="updateSelectedTagsDcnf($event)"
                                v-on:input="updateHistoryState"
                                v-on:tags-loaded="applyStateChange"
                            ></Filters>
                        </div>
                    </div>
                </div>

                <app-links v-if="init"/>

                <a class="btn btn-secondary mb-0 search-report bg-transparent"
                   target="_blank"
                   title="Nahlásit"
                   :href="'https://docs.google.com/forms/d/e/1FAIpQLScmdiN_8S_e8oEY_jfEN4yJnLq8idxUR5AJpFmtrrnvd1NWRw/viewform?usp=pp_url&entry.1025781741=' + currentUrl()">
                    <i class="fas fa-exclamation-triangle p-0"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import AuthorsList from "./components/AuthorsList";
    import SongsList from "./components/SongsList";
    import Filters from "./components/Filters";
    import AppLinks from "./components/AppLinks";
    import Logo from "./components/Logo";

    /**
     * Root search component.
     *
     * Toggles 2 views (SearchHome and SearchResults).
     */
    export default {
        props: {
            "str-prefill": String
        },

        data() {
            return {
                // Search data
                search_string: "",
                selected_songbooks: {},
                selected_languages: {},
                selected_tags: {},

                // dcnf - disjunctive canonical normal form :)
                selected_tags_dcnf: {},

                // View state
                init: true,
                displayFilter: false
            }
        },


        beforeCreate: function () {
            document.body.className = 'home';
        },

        beforeDestroy() {
            document.body.className = '';
        },

        methods: {
            updateSelectedTagsDcnf(event) {
                this.selected_tags_dcnf = event;
            },

            updateHistoryState() {
                if (this.init)
                    return;

                let url = "/search/";
                let params = [];

                params.push("q=" + this.search_string);
                params.push("tags=" + Object.keys(this.selected_tags));
                params.push("langs=" + Object.keys(this.selected_languages));
                params.push("songbooks=" + Object.keys(this.selected_songbooks));

             //   history.pushState(null, "", url + params.join("&"));

                this.$router.push({path: url + params.join("&")})
            },

            applyStateChange(event) {
                let fragments = decodeURIComponent(window.location.href).split('?');

                if (fragments.length === 1) {
                    this.resetState(false);
                    return;
                }

                let params = fragments[1].split('&');

                for (let param of params) {
                    let param_fragments = param.split('=');

                    if (param_fragments[0] === "searchString") {
                        this.search_string = param_fragments[1];
                    }
                    if (param_fragments[0] === "tags") {
                        let obj = {};

                        for (let id of this.getSplittedParam(param_fragments[1])) {
                            obj[id] = true;
                        }

                        this.selected_tags = obj;
                    }
                    if (param_fragments[0] === "langs") {
                        let obj = {};

                        for (let lang of this.getSplittedParam(param_fragments[1])) {
                            obj[lang] = true;
                        }

                        this.selected_languages = obj;
                    }
                    if (param_fragments[0] === "songbooks") {
                        let obj = {};

                        for (let id of this.getSplittedParam(param_fragments[1])) {
                            obj[id] = true;
                        }

                        this.selected_songbooks = obj;
                    }
                }
            },

            getSplittedParam(param) {
                return param.split(',').filter(str => str.length > 0)
            },

            resetState(update_url) {
                this.search_string = "";
                this.selected_tags = {};
                this.selected_languages = {};
                this.selected_songbooks = {};

                if (update_url) {
                    this.updateHistoryState();
                }
            },

            autoInit() {
                if (this.init && this.search_string !== "") {
                    this.init = false;
                }
            },

            currentUrl() {
                return encodeURIComponent(window.location.href);
            }
        },

        mounted() {
            this.search_string = this.strPrefill ? this.strPrefill : "";
            window.onpopstate = this.applyStateChange;

            if (window.location.href.indexOf('?search') > 0) {
                // this.applyStateChange();
                this.init = false;
            }
        },

        components: {
            Logo,
            AppLinks,
            AuthorsList,
            SongsList,
            Filters
        },

        computed: {
            /**
             * Note that there has to be sth together at the line with return,
             * otherwise js will see only return; and don't give a f*ck about the things below.
             *
             * @returns {boolean}
             */
            filters_active() {

                return (
                    Object.keys(this.selected_songbooks).length +
                    Object.keys(this.selected_tags).length +
                    Object.keys(this.selected_languages).length)
                    > 0;
            }
        }
    }
</script>

<style lang="scss">
    .filter-panel {
        display: block;
    }

    .btn.search-report {
        position: fixed;
        bottom: 0;
        right: 0;
        color: #292929;
        opacity: 0.5;
        transition: 0.2s;
    }

    .btn.search-report:hover {
        color: #292929;
        opacity: 1;
    }

    .home {
        position: relative;
    }
</style>
