<template>
    <v-app>
        <div class="content-padding">
            <h1>Vyhledávání</h1>

            <input class="form-control search-basic"
                   placeholder="Zadejte název písně, část textu nebo jméno autora"
                   v-model="search_string"
                   v-on:input="updateQuery()"
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

                    <!-- <div class="card card-green">
                        <div class="card-header">Autoři</div>
                        <div class="card-body">
                            <table class="table">
                                @forelse($authors as $author)
                                    <tr>
                                        <td>
                                            <a href="{{route('client.author',$author)}}">{{$author->getSearchTitle()}}</a>
                                            - {{$author->getSearchText()}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>
                                            <i>Žádný autor nebyl nalezen.</i>
                                        </td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div> -->
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
    </v-app>
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
        this.store.search_string = this.strPrefill;
    },

    components: {
        AuthorsList,
        SongsList,
        Tags
    }
}
</script>
