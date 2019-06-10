<template>
    <div class="card mb-1 overflow-auto media-card">
		<a class="btn btn-secondary float-right fixed-top position-sticky" v-on:click="mediaDisplay=false"><i class="fas fa-times pr-0"></i></a>
		<slot></slot>
    </div>
</template>

<script>
    import { store } from "Public/components/store.js";

    export default {
        data() {
            return store;
        }
    }
</script>