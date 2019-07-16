<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class MyIndexConfigurator extends IndexConfigurator
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
                        'lowercase',
                        'czech_stop',
                        'czech_stemmer'
                    ]
                ]
            ]
        ]
    ];
}