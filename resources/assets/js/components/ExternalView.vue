<template>
    <div class="card card-green" style="margin-bottom: 1em;">
        <div class="card-header py-2" v-if="![4, 8, 9].includes(this.type)">
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
            v-if="type == 1"
            :src="iframeSrc"
            width="100%"
            height="80"
            frameborder="0"
            allowtransparency="true"
            allow="encrypted-media"
        ></iframe>

        <iframe
            v-else-if="type == 2"
            width="100%"
            height="120"
            scrolling="no"
            frameborder="no"
            allow="autoplay"
            :src="iframeSrc"
        ></iframe>

        <div
            class="embed-responsive embed-responsive-16by9"
            v-else-if="type == 3"
        >
            <iframe :src="iframeSrc" frameborder="0" allowfullscreen></iframe>
        </div>

        <!-- audio soubor -->
        <audio controls :src="iframeSrc" v-else-if="type == 7">
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
        type: Number,
        thumbnailUrl: String,
        mediaId: String,
        authors: Array,
        height: Number
    },

    data() {
        return {
            types: {
                0: 'link',
                1: 'spotify',
                2: 'soundcloud',
                3: 'youtube',
                4: 'score',
                5: 'webpage',
                6: 'youtube_channel',
                7: 'audio',
                8: 'pdf/text_chords',
                9: 'pdf/text'
            },
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
            if (this.type == 1) {
                return 'https://open.spotify.com/embed/track/' + this.mediaId;
            } else if (this.type == 2) {
                return (
                    'https://w.soundcloud.com/player/?url=' +
                    this.mediaId +
                    '&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true'
                );
            } else if (this.type == 3) {
                return 'https://www.youtube.com/embed/' + this.mediaId;
            } else if ([4, 8, 9].includes(this.type)) {
                // pdf file
                // decide if the browser can display that directly in iframe
                if (this.browser.satisfies(this.supportPdfIframesCondition)) {
                    return this.url;
                } else {
                    return 'https://docs.google.com/viewer?url=' + this.url;
                }
            } else {
                return this.url;
            }
        },

        mediaLink() {
            if (this.type == 1) {
                return 'https://open.spotify.com/track/' + this.mediaId;
            } else {
                return this.url;
            }
        },

        typeString() {
            return this.types[this.type];
        },

        typeClass() {
            switch (this.type) {
                case 1:
                    return 'fab fa-spotify';
                    break;

                case 2:
                    return 'fab fa-soundcloud';
                    break;

                case 3:
                    return 'fab fa-youtube';
                    break;

                case 4:
                    return 'fas fa-file-pdf';
                    break;

                default:
                    return 'fas fa-music';
                    break;
            }
        }
    }
};
</script>
