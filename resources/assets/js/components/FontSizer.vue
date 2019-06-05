<template>
    <div class="transpose-control-wrapper">
        <span>
            <span>Velikost textu: </span>
            <a class="btn btn-warning" v-if="fontSizePercent !== 100" v-on:click="fontSizePercent = 100">resetovat</a>
            <a class="btn btn-secondary font-weight-bold" v-on:click="resize(20)">+</a>
            <a class="btn btn-secondary font-weight-bold" v-on:click="resize(-20)">-</a>
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
