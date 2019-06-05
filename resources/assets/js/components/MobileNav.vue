<template>
	<span>
		<a class="btn btn-secondary" v-on:click="toolsDisplay=!toolsDisplay"><i class="fas" v-bind:class="{ 'fa-caret-square-down': toolsDisplay, 'fa-caret-square-up': !toolsDisplay }"></i> Nástroje</a>
	</span>
</template>

<script>
    import { store } from "./store.js";

    export default {
        data() {
            return store;
        }
    }
</script>
