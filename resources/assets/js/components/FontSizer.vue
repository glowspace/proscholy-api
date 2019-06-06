<template>
    <span>
        <div class="d-inline-block m-1 py-1 px-2 border rounded text-center">
            <div>Velikost písma</div>
            <div class="btn-group m-0" role="group">
                <a class="btn btn-secondary" v-on:click="resize(-10)">-</a>
                <a class="btn btn-secondary bg-light transpose-window" v-on:click="fontSizePercent = 100">{{ (fontSizePercent - 100)/10 }}</a>
                <a class="btn btn-secondary" v-on:click="resize(10)">+</a>
            </div>
        </div>
    </span>
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
