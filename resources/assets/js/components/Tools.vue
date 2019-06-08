<template>
    <div class="card d-block mb-1 p-1 overflow-auto">
		<a class="btn btn-secondary float-right" v-on:click="toolsDisplay=false"><i class="fas fa-times pr-0"></i></a>
		<transposition></transposition>
		<font-sizer></font-sizer>
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