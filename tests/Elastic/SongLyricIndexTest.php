<?php

namespace Tests\Elastic;

use Tests\TestCase;
use App\Factories\ElasticClientFactory;

use App\SongLyric;
use stdClass;

use function PHPUnit\Framework\isReadable;

class SongLyricIndexTest extends TestCase
{
    protected \Elasticsearch\Client $client;
    protected array $mapping;

    protected $index_name = 'song_lyric_test';

    public function setUp(): void
    {
        parent::setUp();

        $sl = new SongLyric();

        $this->client = (new ElasticClientFactory())->get();
        $this->mapping = (new SongLyric())->getMapping();

        $this->client->indices()->create([
            'index' => $this->index_name,
            'body' => [
                'settings' => $sl->getSettings()
            ]
        ]);
    }

    public function tearDown(): void
    {
        $this->client->indices()->delete([
            'index' => $this->index_name
        ]);
    }

    public function testSongLyricNameAnalyzer()
    {
        $res = $this->client->indices()->analyze([
            'index' => $this->index_name,
            'body' => [
                'analyzer' => 'name_analyzer',
                'text' => 'Chval ho, ó, duše má'
            ]
        ]);

        $toks = array_map(fn ($tok) => $tok['token'], $res['tokens']);
        logger($toks);

        $this->assertContains('chval', $toks);
        $this->assertContains('duse', $toks);

        $res = $this->client->indices()->analyze([
            'index' => $this->index_name,
            'body' => [
                'analyzer' => 'name_analyzer',
                'text' => ['10000 reasons', '10,000 duvodu']
            ]
        ]);

        $toks = array_map(fn ($tok) => $tok['token'], $res['tokens']);
        logger($toks);

        $this->assertContains('10000', $toks);
        $this->assertContains('reasons', $toks);
    }

    public function testPutSongLyricsMapping()
    {
        // https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/index_management.html#_put_mappings_api
        $mappingInfo = [
            'index' => $this->index_name,
            'body' =>
            array_merge(
                ['_source' => ['enabled' => true]],
                $this->mapping
            )
        ];

        $res = $this->client->indices()->putMapping($mappingInfo);

        $this->assertIsArray($res);
        $this->assertTrue($res['acknowledged']);

        // logger($this->client->indices()->getMapping(['index' => $this->index_name]));
    }

    public function testPutIndexingData()
    {
        $params = [
            'index' => $this->index_name,
            'body'  => [
                'name' => '10,000 reasons',
                'name_raw' => ['myname', 'ahoj']
            ]
        ];

        // Document will be indexed to my_index/_doc/<autogenerated ID>
        $res = $this->client->index($params);

        $this->assertIsArray($res);
        $this->assertEquals($res['result'], 'created');

        // We need to wait, until the documets are really searchable
        $this->waitTillReady(1);
    }

    public function waitTillReady($expected_n_documents)
    {
        $n = 0;

        while ($n < $expected_n_documents) {
            $params = [
                'from' => 0,
                'index'  => $this->index_name,
                'body'   => [
                    'query' => [
                        'match_all' => new stdClass()
                    ]
                ]
            ];
    
            $response = $this->client->search($params);
            $n = intval($response['hits']['total']['value']);

            logger("Waiting for records");
            sleep(1);
        }
    }
    
    public function testSearchName()
    {
        $q = '100 raeson';

        $params = [
            'from' => 0,
            'index'  => $this->index_name,
            'body'   => [
                'query' => [
                    'bool' => [
                        'should' => [
                            // fuzzy search, word-by-word
                            ['multi_match' => [
                                'query' => $q,
                                'fields' => ['name'],
                                'fuzziness' => 'AUTO'
                            ]],
                            // search-as-you-type (exact match)
                            ['multi_match' => [
                                'query' => $q,
                                'type' => 'bool_prefix',
                                'fields' => ['name', 'name._2gram', 'name._3gram']
                            ]],
                            // phrase search
                            ['match_phrase' => [
                                'name' => [
                                    'query' => $q,
                                    'slop' => 2
                                ],
                            ]]
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->client->search($params);

        // logger(sprintf("Searching '%s':", $q));
        // logger($response);
    }

    public function testSongLyricTextAnalyzer()
    {
        $res = $this->client->indices()->analyze([
            'index' => $this->index_name,
            'body' => [
                'analyzer' => 'text_phrase_analyser',
                'text' => "Ať srdce mé Tebe vídá,\r\nať srdce mé Tebe zná,\r\nvidět Tě toužím, \r\nvidět Tě toužím.\r\n\r\nChci vidět Krále na trůnu,\r\nzářícího ve světle slávy.\r\nVylej svou lásku a moc,\r\nkdyž zpívám: Svatý, svatý, svatý.\r\n\r\nSvatý, svatý, svatý,\r\nsvatý, svatý, svatý,\r\nsvatý, svatý, svatý,\r\nvidět Tě toužím."
            ]
        ]);

        $toks = array_map(fn ($tok) => $tok['token'], $res['tokens']);
        logger($toks);
    }

    public function testCzechAnalyzer()
    {
        $hymnology = "T: Miloslav Esterle\nM: Motherless Child, nápěv amerického spirituálu / Old Plantation Hymns, 1899 / Černošské spirituály, 1955 / Svítá, 1992 / 2018";

        $hymnology_processed = str_replace('T:', '', $hymnology);
        $hymnology_processed = str_replace('M:', '', $hymnology_processed);
        $hymnology_processed = str_replace('S:', '', $hymnology_processed);

        $res = $this->client->indices()->analyze([
            'index' => $this->index_name,
            'body' => [
                'analyzer' => 'czech_analyzer',
                'text' => $hymnology_processed,
            ]
        ]);

        $toks = array_map(fn ($tok) => $tok['token'], $res['tokens']);
        logger($toks);
    }
}
