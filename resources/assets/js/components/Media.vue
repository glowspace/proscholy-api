<template>
    <div class="card d-block mb-1 overflow-auto">
		<a class="btn btn-secondary float-right" v-on:click="mediaDisplay=false"><i class="fas fa-times pr-0"></i></a>
		Tady patří veškerá multimédia (videa z YouTube i ostatní nahrávky) ve formě karet.
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