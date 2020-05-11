<template>
    <span>
        <a
            class="btn btn-secondary"
            v-bind:class="{ chosen: toolsDisplay }"
            v-on:click="
                toolsDisplay = !toolsDisplay;
                mediaDisplay = false;
            "
        >
            <i class="fas fa-sliders-h"></i>
            <span class="d-none d-sm-inline">Nástroje</span>
        </a>
        <a
            class="btn btn-secondary"
            v-if="hasmediaslot"
            v-bind:class="{ chosen: mediaDisplay }"
            v-on:click="
                mediaDisplay = !mediaDisplay;
                toolsDisplay = false;
            "
        >
            <i class="fas fa-headphones"></i>
            <span class="d-none d-sm-inline">Nahrávky</span>
        </a>
        <div
            class="d-inline-block btn-group m-0"
            role="group"
            v-bind:class="{ chosen: autoscroll }"
        >
            <a class="btn btn-secondary" v-on:click="autoscroll = !autoscroll">
                <i
                    class="fas"
                    v-bind:class="[
                        autoscroll
                            ? 'pr-0 fa-stop-circle'
                            : 'fa-arrow-circle-down'
                    ]"
                ></i>
                <span class="d-none d-sm-inline" v-if="!autoscroll"
                    >Rolovat</span
                > </a
            ><span v-if="autoscroll"
                ><a
                    class="btn btn-secondary font-weight-bold"
                    v-on:click="resize(-10)"
                    >-</a
                ><a
                    class="btn btn-secondary font-weight-bold"
                    v-on:click="resize(10)"
                    >+</a
                ></span
            >
        </div>
    </span>
</template>

<script>
import { store } from "Public/components/store.js";

export default {
    props: ["hasmediaslot"],
    data() {
        return store;
    }
};
</script>
