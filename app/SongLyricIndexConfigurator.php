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
                ],
            ],
            'analyzer' => [
                'rebuilt_czech' => [
                    'tokenizer' => 'standard',
                    'filter' => [
                        'czech_stemmer',
                        'asciifolding',
                        'lowercase',
                        'czech_stop',
                    ]
                ]
            ]
        ]
    ];
}