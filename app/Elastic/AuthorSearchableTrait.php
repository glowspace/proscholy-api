<?php

namespace App\Elastic;

trait AuthorSearchableTrait
{
    use \ElasticScoutDriverPlus\Searchable;

    protected $settings = [
        'analysis' => [
            'char_filter' => [
                'remove_commas' => [
                    'type' => 'mapping',
                    'mappings' => [
                        ', => '
                    ]
                ]
            ],
            'analyzer' => [
                'name_analyzer' => [
                    'tokenizer' => 'standard',
                    'filter' => [
                        'lowercase',
                        'icu_folding'
                    ],
                    'char_filter' => [
                        'remove_commas'
                    ]
                ]
            ]
        ]
    ];

    // the Elasticsearch metadata for attributes retrieved by toSearchableArray()
    protected $mapping = [
        'properties' => [
            // we have the secondary names here in this field
            'name' => [
                'type' => 'search_as_you_type',
                'analyzer' => 'name_analyzer',
                'fields' => [
                    'raw' => [
                        'type' => 'icu_collation_keyword',
                        'index' => false,
                        'language' => 'cs_CZ'
                    ]
                ]
            ]
        ]
    ];

    public function getSettings()
    {
        return $this->settings;
    }

    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'name' => $this->name
        ];
    }

    public function searchableWith()
    {
        return [];
    }
}
