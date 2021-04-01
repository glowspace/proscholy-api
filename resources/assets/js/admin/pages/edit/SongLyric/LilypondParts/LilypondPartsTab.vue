<template>
    <v-layout row wrap class="pt-2">
        <v-flex xs12>
            <v-textarea
                class="auto-grow-alt"
                outline
                name="input-global"
                label="Pomocný kód"
                ref="textarea"
                v-model="lilypondPartsSheetMusic.global_src"
                v-on:keydown.tab.prevent="
                    preventTextareaTab(
                        $event,
                        lilypondPartsSheetMusic,
                        'global_src'
                    )
                "
                style="font-family: monospace; tab-size: 2; margin-bottom: 5px;"
            ></v-textarea>

            <v-btn @click="addPart">Přidat část písně</v-btn>
        </v-flex>

        <template v-for="(part, i) in lilypondPartsSheetMusic.lilypond_parts">
            <v-flex xs12 md6 :key="i * 2">
                <v-select
                    :items="enums.key_major"
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
                    v-on:keydown.tab.prevent="
                        preventTextareaTab($event, part, 'src')
                    "
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
                    :query="gql => fetch_lilypond_part_query"
                    :variables="{
                        lilypond_part: part,
                        global_src: lilypondPartsSheetMusic.global_src
                    }"
                    :debounce="400"
                    @result="cropSvg(`lilypond_src_div_${i}`)"
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
                            v-html="data.lilypond_preview_part.svg"
                            :ref="`lilypond_src_div_${i}`"
                            style="max-height: 70vh; overflow: scroll; white-space: pre;"
                        ></div>

                        <!-- No result -->
                        <div v-else>Náhled lilypondu není dostupný</div>
                    </template>
                </ApolloQuery>
            </v-flex>
        </template>

        <v-flex xs12>
            <v-btn @click="renderFinal">Zobrazit finální noty</v-btn>
            <div v-html="global_svg" ref="lilypond_src_div_total"></div>
        </v-flex>
    </v-layout>
</template>

<script>
import lilypond_helper from 'Admin/helpers/lilypond.js';

export default {
    props: {
        value: {}
    },

    data() {
        // do not forget to update SongLyric.js
        return {
            lilypondPartsSheetMusic: {
                lilypond_parts: [
                    {
                        src: '',
                        name: 'part',
                        key_major: 'c',
                        time_signature: '4/4'
                    }
                ],
                global_src: '',
                global_config: {
                    two_voices_per_staff: false
                }
            },

            global_svg: '',
            fetch_lilypond_part_query: lilypond_helper.queries.part,
            enums: lilypond_helper.enums,
            templates: lilypond_helper.templates
        };
    },

    methods: {
        preventTextareaTab(event, src_obj, src_prop) {
            let originalSelectionStart = event.target.selectionStart,
                textStart = src_obj[src_prop].slice(0, originalSelectionStart),
                textEnd = src_obj[src_prop].slice(originalSelectionStart);

            Vue.set(src_obj, src_prop, `${textStart}\t${textEnd}`);
            event.target.value = src_obj[src_prop]; // required to make the cursor stay in place.
            event.target.selectionEnd = event.target.selectionStart =
                originalSelectionStart + 1;
        },

        cropSvg(src_div) {
            Vue.nextTick().then(() => {
                const svgelem = this.$refs[src_div][0].childNodes[0];

                // var svgelem = this.$refs.lilypond_src_div.childNodes[0];
                var bbox = svgelem.getBBox();
                if (bbox && bbox.width && bbox.height) {
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
        },

        addPart() {
            this.lilypondPartsSheetMusic.lilypond_parts.push({
                src: '',
                name: `part${this.lilypondPartsSheetMusic.lilypond_parts.length}`,
                key_major: 'c',
                time_signature: '4/4'
            });
        },

        renderFinal() {
            this.$apollo
                .query({
                    query: lilypond_helper.queries.total,
                    variables: {
                        lilypond_total: {
                            lilypond_parts: this.lilypondPartsSheetMusic
                                .lilypond_parts,
                            global_src: this.lilypondPartsSheetMusic.global_src
                        }
                    }
                })
                .then(response => {
                    this.global_svg = response.data.lilypond_preview_total.svg;

                    this.cropSvg('lilypond_src_div_total');
                })
                .catch(err => {
                    // this.songLoading = false;
                });
        }
    },

    watch: {
        lilypondPartsSheetMusic: {
            deep: true,
            handler(val) {
                this.$emit('input', val);
            }
        },

        value(val) {
            console.log(val);
            this.lilypondPartsSheetMusic = val;
        }
    }
};
</script>
