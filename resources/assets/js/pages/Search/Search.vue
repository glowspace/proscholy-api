<template>
    <div :class="{'home-init': init}">
        <!-- <div class="logo-wrapper">
            <div class="logo"></div>
            <span class="caption noselect">Zpěvník</span>
        </div> -->
        <div class="row fixed-top position-sticky mt-n4">
            <div class="col-sm-8 pt-5 pb-3">
                <div class="search-wrapper shadow">
                    <input class="search-home"
                        placeholder="Zadejte název písně, část textu nebo jméno autora"
                        v-model="search_string"
                        autofocus><button type="button"
                            class="search-submit" @click="init=false">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body p-0" v-if="!init">
                        <SongsList 
                            v-bind:search-string="search_string"
                            v-bind:selected-tags="selected_tags"
                        ></SongsList>
                    </div>
                </div>
                <!-- <AuthorsList v-bind:search-string="search_string"></AuthorsList> -->
            </div>
            <div class="col-sm-4">
                <Tags v-model="selected_tags"></Tags>
            </div>
        </div>
    </div>
</template>

<script>

import AuthorsList from "./AuthorsList";
import SongsList from "./SongsList";
import Tags from "./Tags";

export default {
    props: {
        "str-prefill": String
    },

    data() {
        return {
            search_string: "",
            selected_tags: {},
            init: true
        }
    },

    methods: {

    },

    mounted() {
        this.search_string = this.strPrefill;
    },

    components: {
        AuthorsList,
        SongsList,
        Tags
    }
}
</script>
