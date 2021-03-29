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

    time_signature: ['4/4', '3/4', '2/4', '6/8']
};

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

import gql from 'graphql-tag';

const FETCH_LILYPOND_TOTAL = gql`
    query($lilypond_total: LilypondTotalInput) {
        lilypond_preview_total(lilypond_total: $lilypond_total) {
            svg
        }
    }
`;

const templates = {
    parts_basic: `\\version "2.22.0"

    solo = \\relative {
    \tc'
    }
    
    soloText = \\lyricmode {
    \t
    }`
};

export default {
    templates,
    enums,
    queries: {
        part: FETCH_LILYPOND_PART,
        total: FETCH_LILYPOND_TOTAL
    }
};
