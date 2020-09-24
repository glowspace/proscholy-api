<template>
    <div class="card card-green" style="margin-bottom: 1em;">
        <div class="card-header py-2" v-if="mediaType !== 'file/pdf'">
            <a :href="mediaLink" target="_blank" title="Otevřít v novém okně"
                ><i :class="typeClass"></i
            ></a>
            <span v-for="(author, index) in authors" v-bind:key="author.id"
                ><span v-if="index">,</span>
                <a :href="author.public_url">{{ author.name }}</a>
            </span>
            <a
                v-if="downloadUrl"
                :href="downloadUrl"
                title="Stáhnout"
                class="float-right"
                ><i class="fas fa-download"></i
            ></a>
        </div>
        <iframe
            v-if="mediaType == 'spotify'"
            :src="iframeSrc"
            width="100%"
            height="80"
            frameborder="0"
            allowtransparency="true"
            allow="encrypted-media"
        ></iframe>

        <iframe
            v-else-if="mediaType == 'soundcloud'"
            width="100%"
            height="120"
            scrolling="no"
            frameborder="no"
            allow="autoplay"
            :src="iframeSrc"
        ></iframe>

        <div
            class="embed-responsive embed-responsive-16by9"
            v-else-if="mediaType == 'youtube'"
        >
            <iframe :src="iframeSrc" frameborder="0" allowfullscreen></iframe>
        </div>

        <!-- audio soubor -->
        <audio
            controls
            :src="iframeSrc"
            v-else-if="
                ['file/mp3', 'file/wav', 'file/flac', 'file/aac'].includes(
                    mediaType
                )
            "
        >
            Váš prohlížeč bohužel nepodporuje přehrávání nahraných souborů.
        </audio>

        <iframe
            v-else
            :src="iframeSrc"
            frameborder="0"
            width="100%"
            :height="height || 300"
        ></iframe>
    </div>
</template>

<script>
import Bowser from 'bowser';

export default {
    props: {
        url: String,
        downloadUrl: String,
        mediaType: Number,
        thumbnailUrl: String,
        mediaId: String,
        authors: Array,
        height: Number,
        isUploaded: Boolean
    },

    data() {
        return {
            browser: Bowser.getParser(window.navigator.userAgent),
            supportPdfIframesCondition: {
                mobile: {
                    chrome: '>1000'
                },
                desktop: {
                    chrome: '>70',
                    firefox: '>60'
                }
            }
        };
    },

    computed: {
        iframeSrc() {
            let previewUrl = this.url;
            if (this.isUploaded) {
                // see DownloadController.php
                previewUrl = this.url + '?nahled=true';
            }

            if (this.mediaType == 'spotify')
                return 'https://open.spotify.com/embed/track/' + this.mediaId;

            if (this.mediaType == 'soundcloud')
                return `https://w.soundcloud.com/player/?url=${this.mediaId}
                    &color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true`;

            if (this.mediaType == 'youtube')
                return 'https://www.youtube.com/embed/' + this.mediaId;

            if (this.mediaType == 'file/pdf') {
                // decide if the browser can display that directly in iframe
                if (this.browser.satisfies(this.supportPdfIframesCondition))
                    return previewUrl;

                return 'https://docs.google.com/viewer?url=' + this.url;
            }

            return previewUrl;
        },

        mediaLink() {
            if (this.mediaType == 'spotify')
                return 'https://open.spotify.com/track/' + this.mediaId;

            return this.url;
        },

        typeClass() {
            switch (this.mediaType) {
                case 'spotify':
                    return 'fab fa-spotify';
                case 'soundcloud':
                    return 'fab fa-soundcloud';
                case 'youtube':
                    return 'fab fa-youtube';
                case 'file/pdf':
                    return 'fas fa-file-pdf';
                default:
                    return 'fas fa-music';
            }
        }
    }
};
</script>
