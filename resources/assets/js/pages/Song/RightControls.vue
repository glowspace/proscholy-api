<template>
		<div class="d-flex flex-column mr-n3">
			<a class="btn btn-secondary m-0" v-on:click="toggleFullscreen"><i class="fas" v-bind:class="[fullscreen?'fa-compress':'fa-expand']"></i></a>
            <a class="btn btn-secondary m-0"><i class="fas fa-columns"></i></a>
            <a class="btn btn-secondary m-0"><i class="fas fa-sun"></i></a>
		</div>
</template>

<script>
    export default {
        methods:{
            toggleFullscreen: function() {
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
            fullscreenChanged: function() {
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
