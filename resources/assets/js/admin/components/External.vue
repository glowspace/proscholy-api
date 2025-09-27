<template>
    <tr v-if="line">
        <td class="p-0 align-middle">
            <a
                class="p-2 w-100 d-inline-flex align-items-center"
                :href="mediaLink"
                target="_blank"
                @click="openPreview($event)"
            >
                <span><i :class="[typeClass, 'pl-1 pr-3']"></i></span>
                <span class="pr-3 w-100">{{ displayName }}</span>
                <span><i :class="[previewClass, 'pl-0 pr-0']"></i></span>
            </a>
        </td>
        <td class="p-0 align-middle">
            <a
                v-if="downloadUrl"
                :href="downloadUrl"
                title="Stáhnout"
                class="p-2"
            ><i class="fas fa-download p-0"></i></a>
        </td>
        <td class="p-2 pl-md-5 align-middle">
            <span v-for="(author, authorIndex) in external.authors" :key="author.id">
                <span v-if="authorIndex">,</span>
                <nuxt-link :to="author.public_route" class="text-secondary">{{
                    author.name
                }}</nuxt-link>
            </span>
        </td>
    </tr>
    <div v-else :class="['card', 'mb-3', {'card-green': !isRegenschori}]">
        <div class="card-header py-2" v-if="!isRegenschori">
            <a
                :href="mediaLink"
                target="_blank"
                title="Otevřít v novém okně"
                @click="openPreview($event)"
            ><i :class="typeClass"></i></a>
            <span v-for="(author, authorIndex) in external.authors" :key="author.id">
                <span v-if="authorIndex">,</span>
                <nuxt-link :to="author.public_route">{{ author.name }}</nuxt-link>
            </span>
            <span class="float-right">
                <a
                    v-if="downloadUrl"
                    :href="downloadUrl"
                    title="Stáhnout"
                    class="mr-2"
                ><i class="fas fa-download"></i></a
                ><a
                    :href="mediaLink"
                    target="_blank"
                    title="Otevřít v novém okně"
                    @click="openPreview($event)"
                ><i class="fas fa-external-link-alt pr-0"></i></a>
            </span>
        </div>

        <div v-if="isRegenschori" class="py-3 px-4 d-flex">
            <div class="ml-n3 mt-n2">
                <a
                    tabindex="0"
                    :class="[
                        'btn btn-secondary rounded-circle drawer-button',
                        {'drawer-button--opened': showIframe},
                        supportsIframe ? 'text-secondary' : 'text-very-muted disabled'
                    ]"
                    @click="showIframe = !showIframe"
                ><i class="fas fa-plus"></i></a>
            </div>
            <div class="flex-grow-1">
                <table class="w-100 external-table">
                    <tbody>
                        <tr>
                            <td colspan="2">
                                <span class="d-flex">
                                    <a
                                        class="w-100 d-inline-flex"
                                        :href="mediaLink"
                                        target="_blank"
                                        @click="openPreview($event)"
                                    >
                                        <span class="px-0"><i :class="typeClass"></i></span>
                                        <span class="pl-2 pr-3 w-100 font-weight-bold">{{ displayName }}</span>
                                        <span><i class="fas fa-external-link-alt"></i></span>
                                    </a>
                                    <a
                                        v-if="downloadUrl"
                                        :href="downloadUrl"
                                        title="Stáhnout"
                                        class="pl-3"
                                    ><i class="fas fa-download p-0"></i></a>
                                </span>
                            </td>
                        </tr>
                        <tr v-if="external.tags_instrumentation.length">
                            <td>Instrum.</td>
                            <td>
                                <span v-for="(tag, tagIndex) in external.tags_instrumentation" :key="tag.id">
                                    <span v-if="tagIndex">,</span>
                                    <span>{{ tag.name }}</span>
                                </span>
                            </td>
                        </tr>
                        <tr v-if="external.catalog_number">
                            <td>Kat. č.</td>
                            <td>{{ external.catalog_number }}</td>
                        </tr>
                        <tr v-if="external.authors.length">
                            <td v-if="external.authors.length == 1">Autor</td>
                            <td v-else>Autoři</td>
                            <td>
                                <span v-for="(author, authorIndex) in external.authors" :key="author.id">
                                    <span v-if="authorIndex">,</span>
                                    <nuxt-link :to="author.public_route">{{
                                        author.name
                                    }}</nuxt-link>
                                </span>
                            </td>
                        </tr>
                        <tr v-if="external.editor">
                            <td>Editor</td>
                            <td>{{ external.editor }}</td>
                        </tr>
                        <tr v-if="external.published_by">
                            <td>Publikoval</td>
                            <td>{{ external.published_by }}</td>
                        </tr>
                        <tr v-if="external.copyright">
                            <td>Copyright</td>
                            <td>{{ external.copyright }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div
            style="font-size:0"
            v-if="supportsIframe && (showIframe || !isRegenschori)"
        >
            <div v-if="!supportsIframe"></div>
            <iframe
                v-else-if="['spotify', 'soundcloud'].includes(external.media_type)"
                :src="source"
                width="100%"
                :height="external.media_type == 'spotify' ? 80 : 120"
                :style="external.media_type == 'spotify' ? 'background-color: #282828;' : ''"
                frameborder="0"
                scrolling="no"
                allowtransparency="true"
                allow="encrypted-media"
            ></iframe>
            <div
                v-else-if="external.media_type == 'youtube'"
                class="embed-responsive embed-responsive-16by9"
            >
                <iframe :src="source" frameborder="0" allowfullscreen></iframe>
            </div>
            <audio
                v-else-if="['file/mp3', 'file/wav', 'file/aac', 'file/flac'].includes(external.media_type)"
                :src="source"
                controls
                class="w-100"
            >Váš prohlížeč bohužel nepodporuje přehrávání zvukových souborů.</audio>
            <div
                v-else-if="['file/jpeg', 'file/png', 'file/gif'].includes(external.media_type)"
                class="overflow-auto"
                :style="'height:' + height"
            >
                <img
                    :src="source"
                    class="w-100"
                />
            </div>
            <iframe
                v-else
                :src="source"
                frameborder="0"
                :style="'width:100%;height:' + height"
                allowfullscreen
            ></iframe>
        </div>
    </div>
</template>

<script>
import Bowser from 'bowser';

export default {
    props: {
        line: Boolean,
        isRegenschori: Boolean,
        index: Number,
        external: Object,
        songName: String,
        height: {
            type: String,
            default: '300px'
        }
    },

    data() {
        return {
            showIframe: false,
            browser: process.client
                ? Bowser.getParser(window.navigator.userAgent)
                : null,
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
        source() {
            if (this.external.media_type == 'spotify') {
                return 'https://open.spotify.com/embed/track/' + this.external.media_id + "?theme=0";
            } else if (this.external.media_type == 'soundcloud') {
                return (
                    'https://w.soundcloud.com/player/?url=' +
                    this.external.media_id +
                    '&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true'
                );
            } else if (this.external.media_type == 'youtube') {
                return 'https://www.youtube-nocookie.com/embed/' + this.external.media_id + (this.external.media_id.includes('?') ? '&' : '?') + 'rel=0';
            } else if (this.external.media_type == 'file/pdf') {
                return 'https://zpevnik.proscholy.cz/js/ViewerJS/#/material/' + this.external.id + '.pdf';
            }

            return this.external.url;
        },

        mediaLink() {
            if (this.external.media_type == 'spotify') {
                return 'https://open.spotify.com/track/' + this.external.media_id;
            }

            return this.external.url;
        },

        downloadUrl() {
            if (this.external.is_uploaded) {
                return this.external.url + '?s=1';
            }

            return false;
        },

        displayName() {
            let name = '';

            if (this.external.caption) {
                name += this.external.caption;
            } else if (this.external.media_type && this.external.media_type.substring(0, 4) == 'file' && this.external.media_id) {
                name += this.external.media_id;
            } else {
                name += this.songName + ' – ' + this.external.content_type_string + ' č. ' + (this.index + 1);
            }

            if (this.external.tags_instrumentation.length && !this.isRegenschori) {
                name += ' (';

                for (let i = 0; i < this.external.tags_instrumentation.length; i++) {
                    const tag = this.external.tags_instrumentation[i];
                    name += i ? ', ' + tag.name : tag.name;
                }

                name += ')';
            }

            return name;
        },

        typeClass() {
            switch (this.external.media_type) {
                case 'spotify':
                case 'soundcloud':
                case 'youtube':
                    return 'fab fa-' + this.external.media_type;

                case 'file/mp3':
                case 'file/wav':
                case 'file/aac':
                case 'file/flac':
                    return 'fas fa-music';

                case 'file/pdf':
                    return 'fas fa-file-pdf';

                case 'file/jpeg':
                case 'file/png':
                case 'file/gif':
                    return 'fas fa-file-image';

                case 'file/docx':
                case 'file/doc':
                    return 'fas fa-file-word';
            }

            if (this.external.media_type && this.external.media_type.substring(0, 4) == 'file') {
                return 'fas fa-file-alt';
            }

            return 'fas fa-link';
        },

        previewClass() {
            if (this.external.content_type == 'RECORDING') {
                return 'far fa-play-circle';
            }

            return 'far fa-eye';
        },

        supportsIframe() {
            return [
                'spotify', 'soundcloud', 'youtube',
                'file/mp3', 'file/wav', 'file/aac', 'file/flac',
                'file/pdf',
                'file/jpeg', 'file/png', 'file/gif',
            ].includes(this.external.media_type);
        }
    },

    methods: {
        openPreview(e) {
            // can be used to override opening preview link
        }
    }
};
</script>
