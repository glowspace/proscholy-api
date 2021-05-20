<template>
    <v-layout row wrap class="pt-2">
        <v-flex xs12>
            <v-layout row wrap>
                <v-flex xs12 md3>
                    <v-select
                        :items="['2.22.0']"
                        v-model="lilypondPartsSheetMusic.score_config.version"
                        label="Verze Lilypond kódu"
                    ></v-select>

                    <v-checkbox
                        class="mt-0"
                        v-model="show_global_src_input"
                        label="Použít pomocný kód"
                    ></v-checkbox>

                    <p v-if="show_global_src_input" style="color: red">
                        POZOR: Pomocný kód se ve výsledném spojeném kódu
                        includuje vícekrát
                    </p>
                </v-flex>

                <v-flex xs12 md6 offset-md3>
                    <v-card class="mb-2">
                        <v-card-title class="py-0 px-3">
                            <h3>Nastavení šablony</h3>
                        </v-card-title>

                        <v-card-text class="py-0 px-3">
                            <v-checkbox
                                class="mt-0"
                                v-model="
                                    lilypondPartsSheetMusic.score_config
                                        .two_voices_per_staff
                                "
                                label="Sloučit dva hlasy do jedné osnovy (soprán + alt, tenor + bas)"
                            ></v-checkbox>

                            <v-checkbox
                                class="mt-0"
                                v-model="
                                    lilypondPartsSheetMusic.score_config
                                        .merge_rests
                                "
                                :disabled="
                                    !lilypondPartsSheetMusic.score_config
                                        .two_voices_per_staff
                                "
                                label="Sloučit pomlky u dvouhlasných osnov"
                            ></v-checkbox>

                            <v-checkbox
                                class="mt-0"
                                v-model="
                                    lilypondPartsSheetMusic.score_config
                                        .note_splitting
                                "
                                label="Automaticky opravit přetékání taktů"
                            ></v-checkbox>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>

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
                browser-autocomplete="off"
            ></v-textarea>
        </v-flex>

        <v-layout
            row
            wrap
            v-for="(part, i) in lilypondPartsSheetMusic.lilypond_parts"
            :key="i"
        >
            <v-flex xs12 md6 :key="i * 2">
                <v-layout row wrap>
                    <v-flex xs12 md4>
                        <v-text-field
                            label="Část (ozn.)"
                            v-model="part.name"
                            :error-messages="partNameError(part.name, i)"
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
                    browser-autocomplete="off"
                ></v-textarea>
            </v-flex>

            <LilypondPartRender
                :name="part.name"
                :src="part.src"
                :key-major="part.key_major"
                :time-signature="part.time_signature"
                :global-src="
                    show_global_src_input
                        ? lilypondPartsSheetMusic.global_src
                        : ''
                "
                :score-config="lilypondPartsSheetMusic.score_config"
                :should-render="isDisplayed"
            ></LilypondPartRender>

            <div style="margin-bottom: 24px; margin-top: -24px;">
                <v-btn @click="insertPart(i)" color="info" outline
                    >Přidat část písně</v-btn
                >
            </div>
        </v-layout>

        <v-flex xs12>
            <v-textarea
                label="Zobrazení po částech"
                v-model="lilypondPartsSheetMusic.sequence_string"
                :error-messages="
                    sequenceStringError(lilypondPartsSheetMusic.sequence_string)
                "
                :placeholder="sequenceStringPlaceholder"
            ></v-textarea>

            <div class="mb-2">
                <a href="#6" @click="downloadLilyPond('ZIP')"
                    >Stáhnout finální Lilypond kód (ZIP)</a
                >
                <a href="#6" @click="downloadLilyPond('PDF')"
                    >Vygenerovat a stáhnout PDF</a
                >
            </div>

            <v-select
                :items="total_variants_select_items"
                v-model="selected_total_variant"
                label="Typ zobrazení"
                @input="
                    renderFinal(total_variants_configs[selected_total_variant])
                "
            ></v-select>

            <!-- <v-text-field
                label="Globální transpozice relativně k c:"
                v-model="global_transpose_relative_c"
            ></v-text-field> -->

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

.lilypond-preview svg {
    white-space: normal;
    width: 100%;
    height: auto;
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
import LilypondPartRender from './LilypondPartRender';

export default {
    props: {
        value: {},
        isDisplayed: false
    },

    components: { LilypondPartRender },

    data() {
        // do not forget to update SongLyric.js
        return {
            lilypondPartsSheetMusic: {
                lilypond_parts: [],
                global_src: '',
                sequence_string: '',
                score_config: {}
            },

            show_global_src_input: false,

            global_svg_loading: false,
            global_svg: '',
            enums: lilypond_helper.enums,
            lilypond_templates: lilypond_helper.templates,

            total_variants_select_items: [
                {
                    text: 'Mobilní zobrazení -- všechny hlasy',
                    value: 'all_voices'
                },
                {
                    text: 'Mobilní zobrazení solo + muži -- zatím pouze náhled',
                    value: 'solo_men'
                },
                {
                    text: 'Mobilní zobrazení solo + ženy -- zatím pouze náhled',
                    value: 'solo_women'
                },
                {
                    text:
                        'Široké zobrazení (všechny hlasy) -- zatím pouze náhled',
                    value: 'all_wide'
                }
            ],

            total_variants_configs: {
                all_voices: {
                    hide_voices: []
                },
                solo_men: {
                    hide_voices: ['sopran', 'alt', 'zeny', 'akordy']
                },
                solo_women: {
                    hide_voices: ['tenor', 'bas', 'muzi', 'akordy']
                },
                all_wide: {
                    paper_width_mm: 240,
                    hide_bar_numbers: false
                }
            },

            selected_total_variant: 'all_voices',

            global_transpose_relative_c: 'c'
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

        insertPart(i) {
            const previous = this.lilypondPartsSheetMusic.lilypond_parts[i];

            this.lilypondPartsSheetMusic.lilypond_parts.splice(i + 1, 0, {
                src: '',
                name: `${this.lilypondPartsSheetMusic.lilypond_parts.length +
                    1}`,
                key_major: previous.key_major,
                time_signature: previous.time_signature
            });
        },

        deletePart(i) {
            Vue.delete(this.lilypondPartsSheetMusic.lilypond_parts, i);
        },

        renderFinal(add_render_config = {}) {
            this.global_svg_loading = true;

            this.$apollo
                .query({
                    query: lilypond_helper.queries.total,
                    variables: {
                        lilypond_total: this.getLilyPondTotalDataObject(
                            add_render_config
                        )
                    },
                    fetchPolicy: 'no-cache'
                })
                .then(response => {
                    this.global_svg = response.data.lilypond_preview_total.svg;
                    this.global_svg_loading = false;
                })
                .catch(err => {
                    this.global_svg_loading = false;
                    console.log(err);
                });
        },

        downloadLilyPond(filetype = 'ZIP') {
            this.$apollo
                .query({
                    query: lilypond_helper.queries.get_file,
                    variables: {
                        lilypond_total: this.getLilyPondTotalDataObject(
                            {
                                'paper_type' : 'a4',
                                'disable_prefilling': true
                            }),
                        file_type: filetype
                    }
                })
                .then(response => {
                    window.open('data:application/octet-stream;base64,' +
                        response.data.lilypond_get_file.base64, '_blank');
                })
                .catch(err => {
                    console.log(err);
                });
        },

        getLilyPondTotalDataObject(add_render_config = {}) {
            return {
                lilypond_parts: this.lilypondPartsSheetMusic.lilypond_parts,
                global_src: this.show_global_src_input
                    ? this.lilypondPartsSheetMusic.global_src
                    : '',
                sequence_string: this.lilypondPartsSheetMusic.sequence_string,
                render_config: {
                    ...this.lilypondPartsSheetMusic.score_config,
                    ...add_render_config,
                    global_transpose_relative_c: this
                        .global_transpose_relative_c
                }
            };
        },

        partNameError(name, part_i) {
            const is_used = this.lilypondPartsSheetMusic.lilypond_parts
                .filter((_, i) => i !== part_i)
                .map(p => p.name)
                .includes(name);

            if (is_used) {
                return 'Duplicitní jméno části';
            }
            if (name.indexOf(' ') >= 0) {
                return 'Jméno části nesmí obsahovat mezery.';
            }
            if (name.indexOf('|') >= 0) {
                return 'Jméno části nesmí obsahovat svislítka.';
            }
            if (name.indexOf('.') >= 0) {
                return 'Jméno části nesmí obsahovat tečky.';
            }

            return null;
        },

        sequenceStringError(str) {
            const tokens = str
                .replace(/\./g, ' . ')
                .replace(/\|/g, ' | ')
                .replace(/\n/g, ' ')
                .split(' ');

            let allowedNames = this.partNamesMap;
            allowedNames.set('|', true);
            allowedNames.set('.', true);

            for (let token of tokens) {
                const trimmed = token.trim();
                if (trimmed.length > 0 && !allowedNames.has(trimmed)) {
                    return 'Neplatný symbol nebo název části: ' + trimmed;
                }
            }
            return null;
        }
    },

    computed: {
        sequenceStringPlaceholder() {
            return this.lilypondPartsSheetMusic.lilypond_parts.reduce(
                (str, part) => str + ` ${part.name}`,
                ''
            );
        },

        globalSrcMaxPixelWidth() {
            const width_mm =
                this.total_variants_configs[this.selected_total_variant]
                    .paper_width_mm ?? 120;

            return width_mm * 4;
        },

        partNamesMap() {
            let m = new Map();
            for (const part of this.lilypondPartsSheetMusic.lilypond_parts) {
                m.set(part.name, true);
            }
            return m;
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
            this.lilypondPartsSheetMusic = val;

            this.show_global_src_input =
                this.lilypondPartsSheetMusic.global_src.length > 0;
        }
    }
};
</script>
