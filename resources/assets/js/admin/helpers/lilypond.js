const enums = {
    key_major: [
        { value: 'c', text: '0 (C / a)' },
        { value: 'g', text: '1# (G / e)' },
        { value: 'd', text: '2# (D / h)' },
        { value: 'a', text: '3# (A / fis)' },
        { value: 'e', text: '4# (E / cis)' },
        { value: 'b', text: '5# (H / gis)' },
        { value: 'fis', text: '6# (Fis / dis)' },
        { value: 'cis', text: '7# (Cis / ais)' },
        { value: 'ces', text: '7b (Ces / as)' },
        { value: 'ges', text: '6b (Ges / es)' },
        { value: 'des', text: '5b (Des / b)' },
        { value: 'as', text: '4b (As / f)' },
        { value: 'es', text: '3b (Es / c)' },
        { value: 'bes', text: '2b (B / g)' },
        { value: 'f', text: '1b (F / d)' },
        { value: 'c', text: '0 (C / a)' },
        { value: 'g', text: '1# (G / e)' },
        { value: 'd', text: '2# (D / h)' },
        { value: 'a', text: '3# (A / fis)' },
        { value: 'e', text: '4# (E / cis)' },
        { value: 'b', text: '5# (H / gis)' },
        { value: 'fis', text: '6# (Fis / dis)' },
        { value: 'cis', text: '7# (Cis / ais)' },
        { value: 'ces', text: '7b (Ces / as)' },
        { value: 'ges', text: '6b (Ges / es)' },
        { value: 'des', text: '5b (Des / b)' },
        { value: 'as', text: '4b (As / f)' },
        { value: 'es', text: '3b (Es / c)' },
        { value: 'bes', text: '2b (B / g)' },
        { value: 'f', text: '1b (F / d)' }
    ],

    time_signature: ['4/4', '3/4', '2/4', '6/8', '2/2', '6/4']
};

const FETCH_LILYPOND_PART = gql`
    query(
        $lilypond_part: LilypondPartInput
        $global_src: String
        $render_config: LilypondRenderConfigInput
    ) {
        lilypond_preview_part(
            lilypond_part: $lilypond_part
            global_src: $global_src
            render_config: $render_config
        ) {
            svg
        }
    }
`;

import gql from 'graphql-tag';

const FETCH_LILYPOND_TOTAL = gql`
    query($lilypond_total: LilypondPartsSheetMusicRenderInput) {
        lilypond_preview_total(lilypond_total: $lilypond_total) {
            svg
        }
    }
`;

const GET_LILYPOND_FILE = gql`
    query(
        $lilypond_total: LilypondPartsSheetMusicRenderInput
        $file_type: RequestedFileType
    ) {
        lilypond_get_file(
            lilypond_total: $lilypond_total
            file_type: $file_type
        ) {
            base64
        }
    }
`;

const templates = {
    parts_basic: `solo = \\relative {
\tc'4
}

soloText = \\lyricmode {
\toh!
}

akordy = \\chordmode {
\tc1:maj
}
`,

    parts_all: `solo = \\relative {
\tc'4 e4. g8
}

soloMale = \\relative { f' b <c e> }

soloText = \\lyricmode {
\t\\set stanza = #"1."
\tS_tex -- tem
}

soloTextI = \\lyricmode {
\t\\set stanza = #"2."
\tS_dal -- ším
}

akordy = \\chordmode {
\tc4:dim e:maj9^7
}

sopran = \\relative {}
alt = \\relative { c'2 ~ c }
altText = \\lyricmode { ú __ }

tenor = \\relative {}
bas = \\relative {}`
};

export default {
    templates,
    enums,
    queries: {
        part: FETCH_LILYPOND_PART,
        total: FETCH_LILYPOND_TOTAL,
        get_file: GET_LILYPOND_FILE
    }
};
