<template>
    <div>
        <h1>Vyhledávání</h1>

        <input class="form-control search-basic mb-2"
                placeholder="Zadejte název písně, část textu nebo jméno autora"
                v-model="search_string"
                autofocus>

        <div class="row">
            <div class="col-sm-8">
                <div class="card card-blue">
                    <div class="card-header">Písně</div>
                    <div class="card-body">
                        <SongsList 
                            v-bind:search-string="search_string"
                            v-bind:selected-tags="selected_tags"
                        ></SongsList>
                    </div>
                </div>

                <div class="card card-green">
                    <div class="card-header">Autoři</div>
                    <div class="card-body">
                        <AuthorsList v-bind:search-string="search_string"></AuthorsList>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card card-red">
                    <div class="card-header">Možnosti vyhledávání</div>
                    <div class="card-body">
                        <Tags v-model="selected_tags"></Tags>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import AuthorsList from "../components/Search/AuthorsList";
import SongsList from "../components/Search/SongsList";
import Tags from "../components/Search/Tags";

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
