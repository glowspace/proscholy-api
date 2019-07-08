<template>
    <tr>
        <td :class="[{'border-top-0': !key}, 'p-0 align-middle']">
            <a class="p-2 w-100 d-inline-block" :href="url" target="_blank">
                <i :class="[typeClass, 'pl-1 pr-2']"></i>{{ name }}
            </a>
        </td>
        <td :class="[{'border-top-0': !key}, 'p-2']">
            <span v-for="(author, authorKey) in authors"><span v-if="authorKey">,</span>
                <a :href="author.public_url" class="text-secondary">{{ author.name }}</a>
            </span>
        </td>
    </tr>
</template>

<script>
import Bowser from "bowser"; 

export default {
    props: {
        key: Number,
        url: String,
        name: String,
        type: Number,
        authors: Array
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
                    chrome: '>70',
                    firefox: ">60",
                }
            }
        }
    },

    computed: {
        viewLink() {
            if ([4,8,9].includes(this.type)) {
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

        downloadLink() {
            return this.url.replace('/preview/', '/download/');
        },

        typeClass() {
            switch (this.type) {
                case 0:
                case 5:
                    return "fas fa-link";
                    break;

                case 4:
                case 8:
                case 9:
                    return "fas fa-file-pdf";
                    break;
            
                default:
                    return "fas fa-file-alt";
                    break;
            }
        }
    }
}
</script>
