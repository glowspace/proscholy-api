<template>
    <span>
        <div class="d-inline-block m-1 py-1 px-2 border rounded text-center">
            <div>Velikost písma</div>
            <div class="btn-group m-0" role="group">
                <a class="btn btn-secondary" v-on:click="resize(-10)">-</a>
                <a class="btn btn-secondary bg-light transpose-window" v-on:click="store.fontSizePercent = 100">{{ (store.fontSizePercent - 100)/10 }}</a>
                <a class="btn btn-secondary" v-on:click="resize(10)">+</a>
            </div>
        </div>
    </span>
</template>

<script>
    import { store } from "Public/components/store.js";

    export default {
        data() { 
            return {
                store: store,
                sl_doc: document.getElementById("song-lyrics"),
                sl_refresh_handler: null
            }
        },

        methods:{
            resize: function(val) {
                let sl = document.getElementById("song-lyrics");

                if (!sl.style.height) {
                    sl.style.height = sl.clientHeight + "px";
                }

                if (this.sl_refresh_handler) {
                    clearTimeout(this.sl_refresh_handler);
                }
                
                this.sl_refresh_handler = setTimeout(() => {
                    sl.style.height = null;
                }, 1000);


                if (this.store.fontSizePercent + val > 70)
                    this.store.fontSizePercent += val;
            }
        }
    }
</script>
