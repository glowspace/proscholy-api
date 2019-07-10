<template>
    <div>
        <h1>Vyhledávání</h1>

        <input class="form-control search-basic mb-2"
                placeholder="Zadejte název písně, část textu nebo jméno autora"
                v-model="search_string"
                autofocus>

        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body p-0">
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
            selected_tags: {}
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
