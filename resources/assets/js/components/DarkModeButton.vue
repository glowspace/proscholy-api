<template>
	<a tabindex="0" class="btn btn-secondary"><i class="fas fa-moon"></i>{{ text }}</a>
</template>

<script>
    import { store } from "./store.js";

    export default {
        props: ["text"],

        data() {
            return store;
        },

        methods:{
            controlsToggle: function() {
				this.controlsDisplay = !(this.controlsDisplay);
				document.querySelector(".navbar.fixed-top").classList.toggle("d-none");
            }
        }
    }
</script>
