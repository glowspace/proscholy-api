<template>
    <div :class="[init?'home-init':'home-afterinit']">
        <div class="logo-wrapper">
            <div class="logo"></div>
            <span class="caption noselect">Zpěvník</span>
        </div>
        <div class="row fixed-top position-sticky mt-n4 justify-content-center zindex-lower">
            <div :class="[{'col-lg-6': init}, 'col-md-8 px-1 pt-5 pb-3 search-column']">
                <div class="search-wrapper shadow">
                    <input class="search-home"
                        placeholder="Zadejte název písně, část textu nebo jméno autora"
                        v-model="search_string"
                        autofocus
                        @keydown="init=false"
                        @click="init=false"
                        ><button type="button"
                            class="search-submit" v-if="init" @click="init=false">
                        <i class="fa fa-search d-none d-sm-inline"></i>
                    </button><button type="button"
                            class="search-submit d-none d-md-inline" v-if="!init">
                        <i class="fa fa-search"></i>
                    </button>
                    <button type="button"
                            class="search-submit d-md-none" v-if="!init" :class="{'filter-active': filters_active, 'filter-open': displayFilter}" @click="displayFilter=!displayFilter">
                        <i class="fa fa-filter"></i>
                    </button>
                </div>
                <div v-if="init" @click="init=false" class="text-center pt-4 text-white"><a class="btn btn-outline-light display-all-songs font-weight-bold"><i class="fas fa-chevron-down pr-1"></i> ZOBRAZIT VŠECHNY PÍSNĚ</a></div>
                <div class="mx-3 d-md-none filter-panel" v-show="!init && displayFilter">
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
                        v-on:change="updateHistoryState"
                    ></Filters>
                </div>
            </div>
            <div class="col-md-4 search-balance"></div>
        </div>
        <div class="row" v-show="!init">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-0">
                        <SongsList 
                            v-bind:search-string="search_string"
                            v-bind:selected-tags-dcnf="selected_tags_dcnf"
                            v-bind:selected-tags="selected_tags"
                            v-bind:selected-songbooks="selected_songbooks"
                            v-bind:selected-languages="selected_languages"
                            v-on:query-loaded="updateHistoryState"
                        ></SongsList>
                    </div>
                </div>
                <!-- <AuthorsList v-bind:search-string="search_string"></AuthorsList> -->
            </div>
            <div class="col-md-4 d-none d-md-block">
                <!-- filters shown only for desktop -->
                <Filters 
                    v-bind:selected-songbooks.sync="selected_songbooks"
                    v-bind:selected-tags.sync="selected_tags"
                    v-bind:selected-languages.sync="selected_languages"
                    v-on:update:selected-tags-dcnf="updateSelectedTagsDcnf($event)"
                    v-on:change="updateHistoryState"
                ></Filters>
            </div>
        </div>
    </div>
</template>

<script>

import AuthorsList from "./AuthorsList";
import SongsList from "./SongsList";
import Filters from "./Filters";

export default {
    props: {
        "str-prefill": String
    },

    data() {
        return {
            search_string: "",
            selected_songbooks: {},
            selected_languages: {},
            selected_tags: {},
            // dcnf - disjunctive canonical normal form :)
            selected_tags_dcnf: {},
            init: true,
            displayFilter: false
        }
    },

    methods: {
        updateSelectedTagsDcnf(event) {
            this.selected_tags_dcnf = event;
        },

        updateHistoryState() {
            let url = "/search?";
            let params = []

            if (this.search_string !== "") {
                params.push("searchString=" + this.search_string);
            }

            if (Object.keys(this.selected_tags).length > 0) {
                params.push("tags=" + Object.keys(this.selected_tags));
            }

            if (Object.keys(this.selected_languages).length > 0) {
                params.push("langs=" + Object.keys(this.selected_languages));
            }

            if (Object.keys(this.selected_songbooks).length > 0) {
                params.push("songbooks=" + Object.keys(this.selected_songbooks));
            }

            history.pushState(null, "", url + params.join("&"));
        },

        applyStateChange(event) {
            let fragments = window.location.href.split('?');
            if (fragments.length > 1) {
                let params = fragments[1].split('&');

                console.log(params);

                for (let param of params) {
                    if (param.substring(0, 13) === "searchString=") {
                        this.search_string = param.substring(14);
                    }
                    if (param.substring(0, 5) === "tags=") {
                        let obj = {};
                        
                        for (let id of param.substring(6).split(',')) {
                            obj[id] = true;
                        }

                        this.selected_tags = obj;
                    }
                    if (param.substring(0, 6) === "langs=") {
                        let obj = {};
                        
                        for (let lang of param.substring(7).split(',')) {
                            obj[lang] = true;
                        }

                        this.selected_languages = obj;
                    }
                    if (param.substring(0, 10) === "songbooks=") {
                        let obj = {};
                        
                        for (let id of param.substring(11).split(',')) {
                            obj[id] = true;
                        }

                        this.selected_songbooks = obj;
                    }
                }
            }
        }
    },

    mounted() {
        this.search_string = this.strPrefill ? this.strPrefill : "";
        window.onpopstate = this.applyStateChange;
    },

    components: {
        AuthorsList,
        SongsList,
        Filters
    },

    computed: {
        filters_active() {
            return 
                Object.keys(this.selected_songbooks).length + 
                Object.keys(this.selected_tags).length + 
                Object.keys(this.selected_languages).length
                 > 0;
        }
    },

    watch: {
        // selected_tags(val, prev) {
        //     this.updateHistoryState();
        // },

        // selected_languages(val, prev) {
        //     this.updateHistoryState();
        // },

        // selected_songbooks(val, prev) {
        //     this.updateHistoryState();
        // }
    }
}
</script>

<style>
.filter-panel {
    display: block;
}
</style>
