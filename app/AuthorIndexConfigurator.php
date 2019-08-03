<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class AuthorIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        'analysis' => [
            'filter' => [
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
                    ]
                ]
            ]
        ]
    ];
}