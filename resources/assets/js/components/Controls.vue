<template>
		<div class="controls fixed-bottom position-sticky p-1" v-bind:class="{'bg-light border-top': controlsDisplay}">
			<tools v-if="toolsDisplay && controlsDisplay"></tools>
			<media v-bind:class="[(mediaDisplay && controlsDisplay)?'d-block':'d-none']"></media>
			<control-buttons v-if="controlsDisplay"></control-buttons>
			<a class="btn btn-secondary float-right" v-on:click="controlsDisplay=!controlsDisplay"><i class="fas pr-0" v-bind:class="[controlsDisplay?'fa-chevron-right':'fa-chevron-left']"></i></a>
		</div>
</template>

<script>
    import { store } from "./store.js";

    export default {
        data() {
            return store;
        }
    }
</script>
