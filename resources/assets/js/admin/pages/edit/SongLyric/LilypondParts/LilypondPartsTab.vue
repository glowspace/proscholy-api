<template>
    <v-layout row wrap class="pt-2">
        <v-flex xs12>
            <v-select
                :items="['2.22.0']"
                v-model="lilypondPartsSheetMusic.global_config.version"
                label="Verze LilyPondu"
            ></v-select>

            <v-checkbox
                class="mt-0"
                v-model="
                    lilypondPartsSheetMusic.global_config.two_voices_per_staff
                "
                label="Sloučit dva hlasy do jedné osnovy (soprán + alt, tenor + bas)"
            ></v-checkbox>

            <v-checkbox
                class="mt-0"
                v-model="lilypondPartsSheetMusic.global_config.merge_rests"
                :disabled="
                    !lilypondPartsSheetMusic.global_config.two_voices_per_staff
                "
                label="Sloučit pomlky u dvouhlasných osnov"
            ></v-checkbox>

            <v-checkbox
                class="mt-0"
                v-model="show_global_src_input"
                label="Použít pomocný kód"
            ></v-checkbox>

            <v-textarea
                class="lilypond-input"
                outline
                v-show="show_global_src_input"
                label="Pomocný kód"
                ref="textarea"
                :rows="10"
                v-model="lilypondPartsSheetMusic.global_src"
                v-on:keydown.tab.prevent="
                    preventTextareaTab(
                        $event,
                        lilypondPartsSheetMusic,
                        'global_src'
                    )
                "
            ></v-textarea>
        </v-flex>

        <template v-for="(part, i) in lilypondPartsSheetMusic.lilypond_parts">
            <v-flex xs12 md6 :key="i * 2">
                <v-layout row wrap>
                    <v-flex xs12 md4>
                        <v-text-field
                            label="Část (ozn.)"
                            v-model="part.name"
                            :error-messages="
                                isPartNameUsed(part.name, i)
                                    ? 'Duplicitní jméno části'
                                    : null
                            "
                        ></v-text-field>
                    </v-flex>

                    <v-flex xs12 md4>
                        <v-select
                            :items="enums.key_major"
                            v-model="part.key_major"
                            label="Předznamenání"
                        ></v-select>
                    </v-flex>

                    <v-flex xs12 md4>
                        <v-combobox
                            v-model="part.time_signature"
                            :items="enums.time_signature"
                            label="Takt"
                        ></v-combobox>
                    </v-flex>

                    <v-flex xs12 md4>
                        <v-checkbox
                            class="mt-0"
                            v-model="part.break_before"
                            label="Vždy zalomit na nový řádek"
                        ></v-checkbox>
                    </v-flex>
                </v-layout>

                <v-layout row wrap>
                    <v-flex xs12 md4>
                        <v-btn
                            :disabled="!!part.src"
                            @click="part.src = lilypond_templates.parts_basic"
                            class="m-0 mb-2 mr-2"
                            >Vložit základní LP</v-btn
                        >
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-btn
                            :disabled="!!part.src"
                            @click="part.src = lilypond_templates.parts_all"
                            class="m-0 mb-2 mr-2"
                            >Vložit LP showcase</v-btn
                        >
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-btn
                            class="m-0 mb-2"
                            :disabled="
                                lilypondPartsSheetMusic.lilypond_parts.length ==
                                    1 && i == 0
                            "
                            @click="deletePart(i)"
                            color="error"
                            outline
                            >Odstranit část</v-btn
                        >
                    </v-flex>
                </v-layout>

                <v-textarea
                    class="lilypond-input auto-grow-alt"
                    outline
                    label="Notový zápis ve formátu Lilypond"
                    v-model="part.src"
                    v-on:keydown.tab.prevent="
                        preventTextareaTab($event, part, 'src')
                    "
                ></v-textarea>

                <!-- <div class="mb-3">
                    <a :href="lilypond_src_download_url" target="_blank"
                        >Stáhnout finální lilypond</a
                    >
                </div> -->
            </v-flex>
            <v-flex xs12 md6 :key="i * 2 + 1">
                <!-- notifyOnNetworkStatusChange fixes the loading state, see https://github.com/vuejs/vue-apollo/issues/263#issuecomment-555326798 -->
                <ApolloQuery
                    :query="fetch_lilypond_part_query"
                    :variables="{
                        lilypond_part: part,
                        global_src: show_global_src_input
                            ? lilypondPartsSheetMusic.global_src
                            : '',
                        global_config: lilypondPartsSheetMusic.global_config
                    }"
                    :debounce="1000"
                    fetchPolicy="no-cache"
                    :options="{ notifyOnNetworkStatusChange: true }"
                    @result="cropSvg(`lilypond_src_div_${i}`)"
                >
                    <template v-slot="{ result: { loading, error, data } }">
                        <div v-if="part.src.length == 0">
                            Začněte psát Lilypond kód pro zobrazení not
                        </div>

                        <template v-else>
                            <div v-if="error">
                                Náhled lilypondu není dostupný (chyba)
                            </div>

                            <div
                                v-else-if="data"
                                v-html="data.lilypond_preview_part.svg"
                                :ref="`lilypond_src_div_${i}`"
                                :class="{
                                    'lilypond-preview': true,
                                    loading: loading
                                }"
                            ></div>

                            <div v-else>Náhled lilypondu není dostupný</div>
                        </template>
                    </template>
                </ApolloQuery>
            </v-flex>
        </template>

        <v-btn @click="addPart" color="info" outline>Přidat část písně</v-btn>

        <v-flex xs12>
            <v-select
                :items="total_variants_select_items"
                v-model="selected_total_variant"
                label="Typ zobrazení"
                @input="
                    renderFinal(total_variants_configs[selected_total_variant])
                "
            ></v-select>

            <v-text-field
                label="Globální transpozice relativně k c:"
                v-model="global_transpose_relative_c"
            ></v-text-field>

            <v-text-field
                label="Zobrazení po částech"
                v-model="global_parts_string"
                :placeholder="partsStringPlaceholder"
            ></v-text-field>

            <v-btn
                class="mb-3"
                @click="
                    renderFinal(total_variants_configs[selected_total_variant])
                "
                >Zobrazit/aktualizovat spojené noty</v-btn
            >
            <div
                v-html="global_svg"
                ref="lilypond_src_div_total"
                :class="{
                    'lilypond-preview': true,
                    loading: global_svg_loading
                }"
                :style="`max-width: ${globalSrcMaxPixelWidth}px`"
            ></div>
        </v-flex>
    </v-layout>
</template>

<style>
.lilypond-preview {
    max-height: 70vh;
    overflow: scroll;
    white-space: pre;
    transition: 200ms opacity;
}

.lilypond-preview.loading {
    opacity: 0.6;
}

.lilypond-input {
    font-family: monospace;
    tab-size: 2;
    margin-bottom: 5px;
}
</style>

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
                lilypond_parts: [],
                global_src: '',
                global_config: {}
            },

            show_global_src_input: false,

            global_svg_loading: false,
            global_svg: '',
            fetch_lilypond_part_query: lilypond_helper.queries.part,
            enums: lilypond_helper.enums,
            lilypond_templates: lilypond_helper.templates,

            total_variants_select_items: [
                {
                    text: 'Výchozí mobilní zobrazení (pouze solo)',
                    value: 'bare_solo'
                },
                { text: 'Mobilní zobrazení solo + muži', value: 'solo_men' },
                { text: 'Mobilní zobrazení solo + ženy', value: 'solo_women' },
                { text: 'Široké zobrazení (všechny hlasy)', value: 'all_wide' }
            ],

            total_variants_configs: {
                bare_solo: {
                    hide_voices: [
                        'sopran',
                        'alt',
                        'tenor',
                        'bas',
                        'zeny',
                        'muzi'
                    ]
                },
                solo_men: {
                    hide_voices: ['sopran', 'alt', 'zeny']
                },
                solo_women: {
                    hide_voices: ['tenor', 'bas', 'muzi']
                },
                all_wide: {
                    paper_width_mm: 240,
                    hide_bar_numbers: false
                }
            },

            selected_total_variant: 'bare_solo',

            global_transpose_relative_c: 'c',
            global_parts_string: ''
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
                // console.log(this.$refs);

                const divelem = Array.isArray(this.$refs[src_div])
                    ? this.$refs[src_div][0]
                    : this.$refs[src_div];
                const svgelem = divelem.childNodes[0];

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
            const last = this.lilypondPartsSheetMusic.lilypond_parts[
                this.lilypondPartsSheetMusic.lilypond_parts.length - 1
            ];

            this.lilypondPartsSheetMusic.lilypond_parts.push({
                src: '',
                name: `${this.lilypondPartsSheetMusic.lilypond_parts.length +
                    1}`,
                key_major: last.key_major,
                time_signature: last.time_signature
            });
        },

        deletePart(i) {
            Vue.delete(this.lilypondPartsSheetMusic.lilypond_parts, i);
        },

        renderFinal(additional_global_config = {}) {
            this.global_svg_loading = true;

            const combined_parts =
                this.global_parts_string == ''
                    ? this.lilypondPartsSheetMusic.lilypond_parts
                    : this.getCombinedParts();

            this.$apollo
                .query({
                    query: lilypond_helper.queries.total,
                    variables: {
                        lilypond_total: {
                            lilypond_parts: combined_parts,
                            global_src: this.show_global_src_input
                                ? this.lilypondPartsSheetMusic.global_src
                                : '',
                            global_config: {
                                ...this.lilypondPartsSheetMusic.global_config,
                                ...additional_global_config,
                                global_transpose_relative_c: this
                                    .global_transpose_relative_c
                            }
                        }
                    },
                    fetchPolicy: 'no-cache'
                })
                .then(response => {
                    this.global_svg = response.data.lilypond_preview_total.svg;

                    this.cropSvg('lilypond_src_div_total');
                    this.global_svg_loading = false;
                })
                .catch(err => {
                    this.global_svg_loading = false;
                    console.log(err);
                });
        },

        getCombinedParts() {
            let part_ids = this.global_parts_string.split(' ');
            let parts_arr = [];

            const part_re = /([\d\w]+)(\[(.{1,2})\])?/;

            const findPart = name =>
                this.lilypondPartsSheetMusic.lilypond_parts.find(
                    p => p.name == name
                );

            for (const part_id of part_ids) {
                const [, name, _, transpose_key] = part_id.match(part_re);
                let part = { ...findPart(name) };
                if (transpose_key) {
                    part.part_transpose = transpose_key;
                }
                parts_arr.push(part);
            }

            console.log(parts_arr);

            return parts_arr;
        },

        isPartNameUsed(name, part_i) {
            return this.lilypondPartsSheetMusic.lilypond_parts
                .filter((_, i) => i !== part_i)
                .map(p => p.name)
                .includes(name);
        }
    },

    computed: {
        partsStringPlaceholder() {
            return this.lilypondPartsSheetMusic.lilypond_parts.reduce(
                (str, part) => str + ` ${part.name}[${part.key_major}]`,
                ''
            );
        },

        globalSrcMaxPixelWidth() {
            const width_mm =
                this.total_variants_configs[this.selected_total_variant]
                    .paper_width_mm ?? 120;

            return width_mm * 4;
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
            // console.log(val);
            this.lilypondPartsSheetMusic = val;

            this.show_global_src_input =
                this.lilypondPartsSheetMusic.global_src.length > 0;
        }
    }
};
</script>
