<template>
    <div
        class="controls fixed-bottom position-sticky p-1"
        v-bind:class="{ 'card-footer': controlsDisplay }"
    >
        <tools
            v-bind:class="[
                toolsDisplay && controlsDisplay ? 'd-block' : 'd-none'
            ]"
        ></tools>
        <media
            v-bind:class="[
                mediaDisplay && controlsDisplay ? 'd-block' : 'd-none'
            ]"
            ><slot name="media"></slot
        ></media>
        <control-buttons
            v-if="controlsDisplay"
            :hasmediaslot="hasMediaSlot"
        ></control-buttons>
        <a class="btn btn-secondary float-right" v-on:click="controlsToggle"
            ><i
                class="fas pr-0"
                v-bind:class="[
                    controlsDisplay ? 'fa-chevron-right' : 'fa-chevron-left'
                ]"
            ></i
        ></a>
    </div>
</template>

<script>
import { store } from "Public/components/store.js";

export default {
    data() {
        return store;
    },
    computed: {
        hasMediaSlot() {
            return !!this.$slots["media"];
        }
    },
    methods: {
        controlsToggle: function() {
            this.controlsDisplay = !this.controlsDisplay;
            document
                .querySelector(".navbar.fixed-top")
                .classList.toggle("d-none");
        }
    }
};
</script>
