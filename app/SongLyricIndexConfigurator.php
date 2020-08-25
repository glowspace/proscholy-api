<?php

namespace App;

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
                'czech_stemmer' => [
                    'type' => 'stemmer',
                    'language' => 'czech'
                ]
            ],
            'analyzer' => [
                'czech_analyzer' => [
                    'tokenizer' => 'standard',
                    'filter' => [
                        'czech_stemmer',
                        'asciifolding',
                        'lowercase',
                        'czech_stop',
                    ]
                ],
                'name_analyzer' => [
                    'tokenizer' => 'my_tokenizer',
                    'filter' => [
                        'czech_stemmer',
                        'asciifolding',
                        'lowercase'
                    ]
                ]
            ],
            'tokenizer' => [
                "my_tokenizer" => [
                    "type" => "edge_ngram",
                    "max_gram" => 10,
                    "token_chars" => [
                        "letter",
                        "digit",
                        "whitespace"
                    ]
                ]
            ]
        ]
    ];
}
