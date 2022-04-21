<?php

namespace Tests\Elastic;

use App\Author;
use Tests\TestCase;
use App\Factories\ElasticClientFactory;

use App\SongLyric;
use stdClass;

use function PHPUnit\Framework\isReadable;

class AuthorIndexTest extends TestCase
{
    protected \Elasticsearch\Client $client;
    protected array $mapping;

    protected $index_name = 'authors_test';

    public function setUp(): void
    {
        parent::setUp();

        $model = new Author();

        $this->client = (new ElasticClientFactory())->get();
        $this->mapping = $model->getMapping();

        $this->client->indices()->create([
            'index' => $this->index_name,
            'body' => [
                'settings' => $model->getSettings()
            ]
        ]);
    }

    public function tearDown(): void
    {
        $this->client->indices()->delete([
            'index' => $this->index_name
        ]);
    }

    public function testSearchSongs()
    {
        $res = SongLyric::search('ahoj')->get();
    }
}
