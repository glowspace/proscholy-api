<?php

namespace App\Elastic;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class SongLyricIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        'analysis' => [
            'filter' => [
                'czech_stop' => [
                    'type' => 'stop',
                    'stopwords' => '_czech_'
                ],
                'czech_hunspell' => [
                    'type' => 'hunspell',
                    'language' => 'cs_CZ'
                ],
                'phrases' => [
                    'type' => 'shingle',
                    "min_shingle_size" => 2,
                    "max_shingle_size" => 3
                ]
            ],
            'char_filter' => [
                'remove_commas' => [
                    'type' => 'mapping',
                    'mappings' => [
                        ', => '
                    ]
                ]
            ],

            // each analyzer uses custom-defined filters, char_filters and tokenizers from above
            // or pre-defined elastic ones (such as 'standard', 'trim', 'unique', ...)
            // see the Elasticsearch docs for more
            // these analyzers can be referenced in the model mapping (see $mapping in <Model>SearchableTrait.php)

            'analyzer' => [
                // czech analyzing inspired by https://www.ludekvesely.cz/serial-elasticsearch-4-fulltextove-vyhledavani-v-cestine/
                'czech_analyzer' => [
                    'tokenizer' => 'standard',
                    'filter' => [
                        // keyword "doubling" and stemming only non-keywords
                        // https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-keyword-repeat-tokenfilter.html
                        'keyword_repeat',
                        'lowercase',
                        // todo: make multilanguage indexes
                        'czech_hunspell',
                        'icu_folding',
                        'remove_duplicates',
                    ]
                ],

                // 'multilanguage' analyzer, for matching up-to-three-word phrases
                'text_phrase_analyser' => [
                    'tokenizer' => 'standard',
                    'filter' => [
                        'lowercase',
                        'icu_folding',
                        'phrases',
                        'unique' // some phrases like svaty, svaty, svaty repeat themselves.. we don't want to prioritize these songs over those that have it only once.. :D
                    ]
                ],

                'name_analyzer' => [
                    'tokenizer' => 'whitespace',
                    'filter' => [
                        'lowercase',
                        'icu_folding'
                    ],
                    'char_filter' => [
                        'remove_commas'
                    ]
                ],
            ]
        ]
    ];
}