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
                <div class="card mb-0 mx-3 p-2 d-block d-md-none filter-panel" v-if="!init && displayFilter">
                    <a class="btn btn-secondary float-right fixed-top position-sticky"
                        v-on:click="displayFilter=false">
                        <i class="fas fa-times pr-0"></i>
                    </a>
                    <!-- <Tags v-model="selected_tags"></Tags> -->
                    <Filters 
                        v-bind:selected-songbooks.sync="selected_songbooks"
                        v-bind:selected-tags.sync="selected_tags"
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
                            v-bind:selected-tags="selected_tags"
                            v-bind:selected-songbooks="selected_songbooks"
                        ></SongsList>
                    </div>
                </div>
                <!-- <AuthorsList v-bind:search-string="search_string"></AuthorsList> -->
            </div>
            <div class="col-md-4 d-none d-md-block">
                <Filters 
                    v-bind:selected-songbooks.sync="selected_songbooks"
                    v-bind:selected-tags.sync="selected_tags"
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
            selected_tags: {},
            init: true,
            displayFilter: false
        }
    },

    methods: {

    },

    mounted() {
        this.search_string = this.strPrefill ? this.strPrefill : "";
    },

    components: {
        AuthorsList,
        SongsList,
        Filters
    },

    computed: {
        filters_active() {
            return Object.keys(this.selected_songbooks).length + Object.keys(this.selected_tags).length > 0;
        }
    }
}
</script>
