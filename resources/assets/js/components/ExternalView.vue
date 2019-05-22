<template>
    <div>
        <iframe v-if="type == 1"
                :src="iframeSrc"
                width="100%"
                height="80"
                frameborder="0"
                allowtransparency="true"
                allow="encrypted-media"></iframe>

        <iframe v-else-if="type == 2"
                        width="100%"
                        height="166"
                        scrolling="no"
                        frameborder="no"
                        allow="autoplay"
                        :src="iframeSrc"></iframe>

        <div class="embed-responsive embed-responsive-16by9" v-else-if="type == 3">
            <iframe :src="iframeSrc"
                    frameborder="0"
                    allowfullscreen></iframe>
        </div>

        <!-- audio soubor -->
        <!-- <iframe v-else-if="type == 7" :src="iframeSrc" frameborder="0" width="100%" height="80"></iframe> -->
        <div v-else-if="type == 7" class="p-2">
            <audio
                controls
                :src="iframeSrc">
                    Váš prohlížeč bohužel nepodporuje přehrávání nahraných souborů.
                </audio>
        </div>

        <iframe v-else :src="iframeSrc" frameborder="0" width="100%" height="500"></iframe>
    </div>
</template>

<script>
// import VueFriendlyIframe from 'vue-friendly-iframe';

export default {
    props: ['url', 'type', 'thumbnail-url', 'media-id'],

    // components: {
    //     VueFriendlyIframe
    // },

    data() {
        return {
            // colors: {
            //     'spotify': '#262b2f',
            //     'soundcloud': '#ff9500',
            //     'youtube': '#db0e0e',
            //     'pdf': '#db0e0e'
            // }
        }
    },

    computed: {
        iframeSrc() {
            if (this.type == 1) {
                return "https://open.spotify.com/embed/track/" + this.mediaId;
            } else if (this.type == 2) {
                return "https://w.soundcloud.com/player/?url=" + this.mediaId + 
                    "&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true";
            } else if (this.type == 3) {
                return "https://www.youtube.com/embed/" + this.mediaId
            } else {
                return this.url;
            }
        }
    }
}
</script>
