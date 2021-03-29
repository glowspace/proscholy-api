<template>
    <v-layout row wrap class="pt-2">
        <v-flex xs12>
            <v-textarea
                class="auto-grow-alt"
                outline
                name="input-global"
                label="Pomocný kód"
                ref="textarea"
                v-model="global.src"
                v-on:keydown.tab.prevent="preventTextareaTab($event, global)"
                style="font-family: monospace; tab-size: 2; margin-bottom: 5px;"
            ></v-textarea>
        </v-flex>

        <template v-for="(part, i) in parts">
            <v-flex xs12 md6 :key="i * 2">
                <v-select
                    :items="['c', 'd']"
                    v-model="part.key_major"
                    label="Předznamenání"
                ></v-select>

                <v-btn :disabled="!!part.src" @click="part.src = 'ahoj'"
                    >Vložit základní Lilypond vzor</v-btn
                >

                <v-textarea
                    class="auto-grow-alt"
                    outline
                    name="input-7-4"
                    label="Notový zápis ve formátu Lilypond"
                    ref="textarea"
                    v-model="part.src"
                    v-on:keydown.tab.prevent="preventTextareaTab($event, part)"
                    style="font-family: monospace; tab-size: 2; margin-bottom: 5px;"
                ></v-textarea>

                <!-- <div class="mb-3">
                    <a :href="lilypond_src_download_url" target="_blank"
                        >Stáhnout finální lilypond</a
                    >
                </div> -->
            </v-flex>
            <v-flex xs12 md6 :key="i * 2 + 1">
                <ApolloQuery
                    :query="gql => queries.fetch_lilypond_part"
                    :variables="{ lilypond_part: part, global_src: global.src }"
                    :update="partLoaded"
                >
                    <template v-slot="{ result: { error, data }, isLoading }">
                        <!-- Loading -->
                        <div v-if="isLoading">Načítání...</div>

                        <!-- Error -->
                        <div v-else-if="error">
                            Náhled lilypondu není dostupný
                        </div>

                        <div
                            v-else-if="data"
                            v-html="data.svg"
                            :ref="`lilypond_src_div_${i}`"
                            style="max-height: 70vh; overflow: scroll; white-space: pre;"
                        ></div>

                        <!-- No result -->
                        <div v-else>Náhled lilypondu není dostupný</div>
                    </template>
                </ApolloQuery>
            </v-flex>
        </template>
    </v-layout>
</template>

<script>
import gql from 'graphql-tag';

const FETCH_LILYPOND_PART = gql`
    query($lilypond_part: LilypondPartInput, $global_src: String) {
        lilypond_preview_part(
            lilypond_part: $lilypond_part
            global_src: $global_src
        ) {
            svg
        }
    }
`;

export default {
    data() {
        return {
            parts: [
                {
                    src: '',
                    name: 'part',
                    key_major: 'c',
                    time_signature: '4/4'
                }
            ],
            global: {
                src: '',
                config: {}
            },
            queries: {
                fetch_lilypond_part: FETCH_LILYPOND_PART
            }
        };
    },

    methods: {
        preventTextareaTab(event, src_obj) {
            let originalSelectionStart = event.target.selectionStart,
                textStart = src_obj.src.slice(0, originalSelectionStart),
                textEnd = src_obj.src.slice(originalSelectionStart);

            Vue.set(src_obj, 'src', `${textStart}\t${textEnd}`);
            event.target.value = src_obj.src; // required to make the cursor stay in place.
            event.target.selectionEnd = event.target.selectionStart =
                originalSelectionStart + 1;
        },

        partLoaded(data) {
            // do the svg

            return data;
        },

        cropSvg(svgelem) {
            Vue.nextTick().then(() => {
                // var svgelem = this.$refs.lilypond_src_div.childNodes[0];
                var bbox = svgelem.getBBox();
                if (bbox && bbox.width && bbox.height) {
                    console.log('cropping the svg');

                    svgelem.setAttribute(
                        'viewBox',
                        [
                            bbox.x,
                            bbox.y,
                            Math.max(60, bbox.width),
                            bbox.height
                        ].join(' ')
                    );
                    svgelem.removeAttribute('width');
                    svgelem.removeAttribute('height');
                }
            });
        }
    }
};
</script>
