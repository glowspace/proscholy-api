<template>
		<div class="d-flex flex-column mr-n3 position-relative">
			<a class="btn btn-secondary m-0" :title="[!fullscreen?'Zobrazit na celou obrazovku':'Zrušit zobrazení na celou obrazovku']" v-on:click="toggleFullscreen"><i class="fas" :class="[fullscreen?'fa-compress':'fa-expand']"></i></a>
            <!-- <a class="btn btn-secondary m-0"><i class="fas fa-columns"></i></a> -->
            <a class="btn btn-secondary m-0 nosleep" :title="[!nosleep?'Blokovat zhasínání displeje':'Přestat blokovat zhasínání displeje']" v-on:click="toggleNosleep"><i class="fa-sun" :class="[nosleep?'far':'fas']"></i></a>
            <a class="btn btn-secondary nosleep-caption">{{ !nosleep?'zhasínání&nbsp;displeje není&nbsp;blokováno':'zhasínání&nbsp;displeje je&nbsp;blokováno' }}</a>
		</div>
</template>

<style>
    .nosleep-caption {
        position: absolute;
        bottom: 0;
        right: 0;
        margin-right: calc(2.875rem);
        margin-bottom: -0.46875rem;
        padding: 0.46875rem 10px;
        background-color: rgba(255, 255, 255, 0.8) !important;
        border-radius: 10px;
        z-index: 4;
        visibility: hidden;
        opacity: 0;
        transition: opacity 600ms, visibility 600ms;
        pointer-events: none;
    }

    body.dark .nosleep-caption {
        background-color: rgba(0, 0, 0, 0.8) !important;
    }

    .nosleep:hover + .nosleep-caption {
        visibility: visible;
        opacity: 1;
    }
</style>

<script>
    import NoSleep from 'nosleep.js';

    export default {
        data() {
            return {
                fullscreen: false,
                nosleep: false,

                noSleeper: new NoSleep()
            }
        },

        methods:{
            toggleFullscreen() {
                var element = document.documentElement;
                var isFullscreen = document.webkitIsFullScreen || document.mozFullScreen || false;
                element.requestFullScreen = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || function () { return false; };
                document.cancelFullScreen = document.cancelFullScreen || document.webkitCancelFullScreen || document.mozCancelFullScreen || function () { return false; };
                if (isFullscreen) {
                    document.cancelFullScreen();
                } else {
                    element.requestFullScreen();
                }
            },

            toggleNosleep() {
                this.nosleep = !this.nosleep;

                if (this.nosleep) {
                    this.noSleeper.enable();
                } else {
                    this.noSleeper.disable();
                }
            },

            fullscreenChanged() {
                this.fullscreen = !(this.fullscreen);
            }
        },
        
        mounted() {
            document.addEventListener("fullscreenchange", this.fullscreenChanged);
            document.addEventListener("mozfullscreenchange", this.fullscreenChanged);
            document.addEventListener("webkitfullscreenchange", this.fullscreenChanged);
            document.addEventListener("msfullscreenchange", this.fullscreenChanged);
        }
    }
</script>
