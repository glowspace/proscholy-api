<template>
    <v-flex xs12 md6>
        <v-radio-group row flex v-model="selected_render_config">
            <v-radio label="vše" value="all"></v-radio>
            <v-radio label="solo/akordy" value="solo_chords"></v-radio>
            <v-radio label="sbor ženy" value="women"></v-radio>
            <v-radio label="sbor muži" value="men"></v-radio>
        </v-radio-group>

        <!-- notifyOnNetworkStatusChange fixes the loading state, see https://github.com/vuejs/vue-apollo/issues/263#issuecomment-555326798 -->
        <ApolloQuery
            :query="fetch_lilypond_part_query"
            :variables="{
                lilypond_part: {
                    name: name,
                    src: src,
                    key_major: keyMajor,
                    time_signature: timeSignature
                },
                global_src: globalSrc,
                global_config: {
                    ...globalConfig,
                    hide_voices: render_configs[selected_render_config]
                }
            }"
            :debounce="1000"
            fetchPolicy="no-cache"
            :options="{ notifyOnNetworkStatusChange: true }"
            @result="cropSvg"
        >
            <template v-slot="{ result: { loading, error, data } }">
                <div v-if="src.length == 0">
                    Začněte psát Lilypond kód pro zobrazení not
                </div>

                <template v-else>
                    <div v-if="error">
                        Náhled lilypondu není dostupný (chyba)
                    </div>

                    <div
                        v-else-if="data"
                        v-html="data.lilypond_preview_part.svg"
                        ref="lilypond_src_div"
                        :class="{
                            'lilypond-preview': true,
                            'ml-4': true,
                            loading: loading
                        }"
                    ></div>

                    <div v-else>Náhled lilypondu není dostupný</div>
                </template>
            </template>
        </ApolloQuery>
    </v-flex>
</template>

<script>
import lilypond_helper from 'Admin/helpers/lilypond.js';
import cropSvgElem from './svgcrop.js';

export default {
    props: [
        'src',
        'name',
        'key-major',
        'time-signature',
        'global-src',
        'global-config'
    ],

    data() {
        return {
            selected_render_config: 'all',
            render_configs: {
                all: [],
                solo_chords: ['muzi', 'zeny', 'sopran', 'alt', 'tenor', 'bas'],
                men: ['solo', 'akordy', 'zeny', 'sopran', 'alt'],
                women: ['solo', 'akordy', 'muzi', 'tenor', 'bas']
            },

            fetch_lilypond_part_query: lilypond_helper.queries.part
        };
    },

    methods: {
        cropSvg() {
            Vue.nextTick().then(() => {
                if (this.$refs['lilypond_src_div'].childNodes.length) {
                    cropSvgElem(this.$refs['lilypond_src_div'].childNodes[0]);
                }
            });
        }
    }
};
</script>
