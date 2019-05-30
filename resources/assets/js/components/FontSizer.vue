<template>
    <div class="transpose-control-wrapper">
        <span>
            <a class="btn btn-secondary" v-on:click="fontSizePercent = 100">Velikost písma<sup>{{ (fontSizePercent - 100)/10 }}</sup></a>
            <a class="btn btn-secondary font-weight-bold" v-on:click="resize(-10)">-</a>
            <a class="btn btn-secondary font-weight-bold" v-on:click="resize(10)">+</a>
        </span>
    </div>
</template>

<script>
    import { store } from "./store.js";

    export default {
        data() {
            return store;
        },

        methods:{
            resize: function(val) {
                if (this.fontSizePercent + val > 70)
                    this.fontSizePercent += val;
            }
        }
    }
</script>
