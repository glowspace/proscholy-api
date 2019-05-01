<template>
    <input class="form-control search-basic"
                   placeholder="Zadejte název písně, část textu nebo jméno autora"
                   v-bind:value="store.search_string"
                   autofocus>
</template>

<script>

    import { store } from "./store.js";

    export default {
        props: ['str'],

        data() {
            return {
                store: store,
                // custom data here
                // abc: ""
            }
        },

        methods: {

        },

        mounted() {
            this.store.search_string = this.str;
        }
    }
</script>

