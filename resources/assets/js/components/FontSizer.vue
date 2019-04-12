<template>
    <div class="transpose-control-wrapper">
        <span>
            <span>Velikost fontu: </span>
            <a class="btn btn-secondary" v-on:click="fontSizePercent = 100">100 %</a>
            <a class="btn btn-secondary" v-on:click="resize(10)">zvětšit</a>
            <a class="btn btn-secondary" v-on:click="resize(-10)">zmenšit</a>
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
                if (this.fontSizePercent + val > 0)
                    this.fontSizePercent += val;
            }
        }
    }
</script>
