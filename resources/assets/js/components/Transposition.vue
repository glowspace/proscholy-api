<template>
    <div class="transpose-control-wrapper" style="display: inline-block">
        <span>Transpozice: </span>
        <a class="btn btn-secondary" v-on:click="transposition = 0">0</a>
        <a class="btn btn-secondary" v-on:click="transpose(1)">+1</a>
        <a class="btn btn-secondary" v-on:click="transpose(-1)">-1</a>
        <a class="btn btn-secondary" v-on:click="useFlatScale = !useFlatScale">{{ useFlatScale ? 'b' : '#' }}</a>
    </div>
</template>

<script>
    import { store } from "./store.js";

    export default {
        data() {
            return store;
        },

        methods:{
            transpose: function(val) {
                this.transposition = (this.transposition + val) % 12;
                if (this.transposition < 0) {
                    this.transposition = 12 + this.transposition;
                }
                console.log(val);
            }
        }
    }
</script>
