<template>
    <div class="tools-wrapper card">
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