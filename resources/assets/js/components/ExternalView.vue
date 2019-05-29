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
import Bowser from "bowser"; 

export default {
    props: {
        url: String,
        type: Number,
        thumbnailUrl: String,
        mediaId: String
    },

    data() {
        return {
            types: {
                0: "link",
                1: "spotify",
                2: "soundcloud",
                3: "youtube",
                4: "score",
                5: "webpage",
                6: "youtube_channel",
                7: "audio",
                8: "pdf/text_chords",
                9: "pdf/text",
            },
            browser: Bowser.getParser(window.navigator.userAgent),
            supportPdfIframesCondition: {
                mobile: {
                    chrome: '>1000',
                },
                desktop: {
                    chrome: '>70'
                }
            }
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
            } else if ([4,8,9].includes(this.type)) {
                // pdf file
                // decide if the browser can display that directly in iframe
                if (this.browser.satisfies(this.supportPdfIframesCondition)) {
                    return this.url;
                } else {
                    return "https://docs.google.com/viewer?url=" + this.url + "&embedded=true";
                }

            } else {
                return this.url;
            }
        },

        typeString() {
            return this.types[this.type];
        }
    }
}
</script>
