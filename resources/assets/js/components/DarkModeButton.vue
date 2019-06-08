<template>
	<a tabindex="0" v-on:click="darkModeToggle" class="btn btn-secondary"><i class="fas fa-moon"></i>{{ text }}</a>
</template>

<script>
    import { store } from "./store.js";

    export default {
        props: ["text"],

        data() {
            return store;
        },

        methods:{
            darkModeToggle: function() {
				document.getElementsByTagName("body")[0].classList.toggle("dark");
            }
        }
    }
</script>
