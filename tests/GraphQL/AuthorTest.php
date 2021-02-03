<?php

namespace Tests\GraphQL;

use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

use App\Author;

class AuthorTest extends BaseTestCase
{
    use CreatesApplication;
    use MakesGraphQLRequests;
    use RefreshDatabase;

    public function testQueriesAuthors(): void
    {
        $author = factory(Author::class)->create();

        $this->graphQL(
            /** @lang GraphQL */
            '
        {
            authors {
                id 
                name
                description
            }
        }
        '
        )->assertJson([
            'data' => [
                'authors' => [
                    [
                        'id' => $author->id,
                        'name' => $author->name,
                        'description' => $author->description,
                    ]
                ]
            ]
        ]);
    }
}
