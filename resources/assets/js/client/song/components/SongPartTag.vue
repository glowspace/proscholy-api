<template>
    <span class="song-part-tag" v-bind:style="{ fontSize: chordSharedStore.fontSizePercent + '%' }">
        <slot></slot>
    </span>
</template>

<script>
    import { store } from "../store.js";

    export default {
        data() {
            return {
                chordSharedStore: store
            }
        },
    }
</script>

<style lang="scss">
.song-part-tag {
    display: inline-block;
    position: relative;
    z-index: 2;
    margin-right: 0.1rem;
  }
</style>
