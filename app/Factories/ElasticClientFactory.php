<?php

namespace App\Factories;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;


class ElasticClientFactory
{
    private Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
                        ->setHosts(config('elastic.client.hosts'))
                        ->build();
    }

    public function get() : Client
    {
        return $this->client;
    }
}
