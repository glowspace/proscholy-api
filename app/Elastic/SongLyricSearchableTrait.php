<?php

namespace App\Elastic;

trait SongLyricSearchableTrait
{
    use \ElasticScoutDriverPlus\Searchable;

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
            ],
            'name_raw' => [
                'type' => 'icu_collation_keyword',
                'index' => false,
                'language' => 'cs_CZ'
            ],

            'lyrics' => [
                'type' => 'text',
                'analyzer' => 'czech_analyzer'
            ],
            'authors' => [
                'type' => 'search_as_you_type',
                'analyzer' => 'name_analyzer'
            ],
            'songook_records' => [
                'type' => 'nested',
                'properties' => [
                    'songbook_id' => [
                        'type' => 'keyword'
                    ],
                    'songbook_number' => [
                        'type' => 'keyword'
                    ],
                    'songbook_number_integer' => [
                        'type' => 'integer'
                    ],
                    'songbook_full_number' => [
                        'type' => 'keyword'
                    ]
                ]
            ],
            'tag_ids' => [
                'type' => 'keyword'
            ],
            'lang' => [
                'type' => 'keyword'
            ],
            'is_arrangement' => [
                'type' => 'boolean'
            ],
            'song_number' => [
                'type' => 'keyword'
            ],
            'song_number_integer' => [
                'type' => 'integer'
            ],
            'has_media_files_externals' => [
                'type' => 'boolean'
            ],
            'has_score_files_externals' => [
                'type' => 'boolean'
            ],
            'has_lyrics' => [
                'type' => 'boolean'
            ],
            'has_chords' => [
                'type' => 'boolean'
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
        $songbook_records = $this->songbook_records()->get()->map(function ($sb) {
            return [
                'songbook_id' => $sb->id,
                'songbook_number' => $sb->pivot->number,
                'songbook_number_integer' => (int)preg_replace('/\D/', '', $sb->pivot->number),
                'songbook_full_number' => [$sb->pivot->songbook->shortcut . $sb->pivot->number, $sb->pivot->songbook->shortcut . ' ' . $sb->pivot->number],
            ];
        });

        // todo: filter only 'associated' memberships
        // todo: add externals' interpreters authors
        $all_authors = $this->authors()->with('memberships')->get();
        foreach ($all_authors as $author) {
            $all_authors = $all_authors->concat($author->memberships);
        }

        // get all song's tags
        $tag_ids = $this->tags()->select('tags.id')->get()->pluck('id');

        $interestingExternals = $this->externals()
            ->with('tags')
            ->whereHas(
                'tags',
                function ($q) {
                    return $q->instrumentation();
                }
            )
            ->get();

        // adjoin externals' instrumentation tags
        foreach ($interestingExternals as $external) {
            $tag_ids = $tag_ids->concat(
                $external->tags()->instrumentation()->select('id')->get()->pluck('id')
            );
        }

        $names = [$this->name];
        if ($this->secondary_name_1) $names[] = $this->secondary_name_1;
        if ($this->secondary_name_2) $names[] = $this->secondary_name_2;

        $arr = [
            'name' => $names,
            // name_keyword is used for sorting
            'name_raw' => join(' ', $names),
            'song_number' => $this->song_number,
            'song_number_integer' => (int)$this->song_number,
            'lyrics' => $this->lyrics_no_chords_no_comments,
            'authors' => $all_authors->pluck('name'),
            'songbook_records' => $songbook_records,
            'lang' => $this->lang,
            'is_arrangement' => $this->is_arrangement,
            'only_regenschori' => (bool)$this->only_regenschori,
            'tag_ids' => $tag_ids,
            'has_media_files_externals' => $this->externals()->media()->count() + $this->files()->audio()->count() > 0,
            'has_score_files_externals' => $this->scoreExternals()->count() + $this->scoreFiles()->count() > 0,
            'has_lyrics' => $this->has_lyrics, // a computed attribute
            'has_chords' => (bool)$this->has_chords // an actual field precomputed in SongLyricSaved event
        ];

        return $arr;
    }

    public function searchableWith()
    {
        return ['authors', 'authors.memberships', 'songbook_records', 'externals', 'externals.tags'];
    }
}
