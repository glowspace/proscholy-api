<?php

namespace Tests\Elastic;

use Tests\TestCase;
use App\Factories\ElasticClientFactory;

use App\SongLyric;
use stdClass;

use function PHPUnit\Framework\isReadable;

class SongLyricSearchTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testSearchSongs()
    {
        $res = SongLyric::searchQuery([
            'multi_match' => [
                'query' => 'reasons',
                'fields' => ['name', 'lyrics']
            ]
        ])
            ->load(['authors'])
            ->paginate(1, 'page', 0)->onlyModels();

        logger($res);
    }
}
