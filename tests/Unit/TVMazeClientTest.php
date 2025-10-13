<?php

declare(strict_types=1);

namespace TVMaze\Tests\Unit;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TVMaze\Client\TVMazeClient;
use TVMaze\Exception\ClientException;
use TVMaze\Exception\RateLimitException;
use TVMaze\Exception\ServerException;
use TVMaze\Model\Episode;
use TVMaze\Model\Person;
use TVMaze\Model\Show;

class TVMazeClientTest extends TestCase
{
    private MockHandler $mockHandler;

    private TVMazeClient $client;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);

        $guzzleClient = new GuzzleClient([
            'handler' => $handlerStack,
            'base_uri' => 'https://api.tvmaze.com',
        ]);

        $this->client = new TVMazeClient(
            $guzzleClient,
            new HttpFactory(),
            new HttpFactory()
        );
    }

    public function testSearchShows(): void
    {
        $mockResponse = [
            [
                'score' => 0.99,
                'show' => [
                    'id' => 1,
                    'name' => 'Test Show',
                    'url' => 'https://www.tvmaze.com/shows/1/test-show',
                    'type' => 'Scripted',
                    'language' => 'English',
                    'genres' => ['Drama'],
                    'status' => 'Running',
                    'runtime' => 60,
                    'averageRuntime' => 60,
                    'premiered' => '2010-01-01',
                    'ended' => null,
                    'officialSite' => 'https://testshow.com',
                    'schedule' => [
                        'time' => '21:00',
                        'days' => ['Sunday'],
                    ],
                    'rating' => [
                        'average' => 8.5,
                    ],
                    'weight' => 95,
                    'network' => null,
                    'webChannel' => null,
                    'dvdCountry' => null,
                    'externals' => [
                        'tvrage' => 12345,
                        'thetvdb' => 67890,
                        'imdb' => 'tt1234567',
                    ],
                    'image' => [
                        'medium' => 'https://static.tvmaze.com/uploads/images/medium_portrait/1/1.jpg',
                        'original' => 'https://static.tvmaze.com/uploads/images/original_untouched/1/1.jpg',
                    ],
                    'summary' => 'A test show',
                    'updated' => 1640995200,
                    '_links' => [
                        'self' => ['href' => 'https://api.tvmaze.com/shows/1'],
                    ],
                ],
            ],
        ];

        $this->mockHandler->append(new Response(200, [], json_encode($mockResponse)));

        $result = $this->client->searchShows('test');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals(0.99, $result[0]['score']);
        $this->assertEquals('Test Show', $result[0]['show']['name']);
    }

    public function testSingleShowSearch(): void
    {
        $mockResponse = [
            'id' => 1,
            'name' => 'Test Show',
            'url' => 'https://www.tvmaze.com/shows/1/test-show',
            'type' => 'Scripted',
            'language' => 'English',
            'genres' => ['Drama'],
            'status' => 'Running',
            'runtime' => 60,
            'averageRuntime' => 60,
            'premiered' => '2010-01-01',
            'ended' => null,
            'officialSite' => 'https://testshow.com',
            'schedule' => [
                'time' => '21:00',
                'days' => ['Sunday'],
            ],
            'rating' => [
                'average' => 8.5,
            ],
            'weight' => 95,
            'network' => null,
            'webChannel' => null,
            'dvdCountry' => null,
            'externals' => [
                'tvrage' => 12345,
                'thetvdb' => 67890,
                'imdb' => 'tt1234567',
            ],
            'image' => [
                'medium' => 'https://static.tvmaze.com/uploads/images/medium_portrait/1/1.jpg',
                'original' => 'https://static.tvmaze.com/uploads/images/original_untouched/1/1.jpg',
            ],
            'summary' => 'A test show',
            'updated' => 1640995200,
            '_links' => [
                'self' => ['href' => 'https://api.tvmaze.com/shows/1'],
            ],
        ];

        $this->mockHandler->append(new Response(200, [], json_encode($mockResponse)));

        $result = $this->client->singleShowSearch('test');

        $this->assertInstanceOf(Show::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test Show', $result->name);
    }

    public function testSingleShowSearchNotFound(): void
    {
        $this->mockHandler->append(new Response(404, [], 'Not Found'));

        $result = $this->client->singleShowSearch('nonexistent');

        $this->assertNull($result);
    }

    public function testGetShow(): void
    {
        $mockResponse = [
            'id' => 1,
            'name' => 'Test Show',
            'url' => 'https://www.tvmaze.com/shows/1/test-show',
            'type' => 'Scripted',
            'language' => 'English',
            'genres' => ['Drama'],
            'status' => 'Running',
            'runtime' => 60,
            'averageRuntime' => 60,
            'premiered' => '2010-01-01',
            'ended' => null,
            'officialSite' => 'https://testshow.com',
            'schedule' => [
                'time' => '21:00',
                'days' => ['Sunday'],
            ],
            'rating' => [
                'average' => 8.5,
            ],
            'weight' => 95,
            'network' => null,
            'webChannel' => null,
            'dvdCountry' => null,
            'externals' => [
                'tvrage' => 12345,
                'thetvdb' => 67890,
                'imdb' => 'tt1234567',
            ],
            'image' => [
                'medium' => 'https://static.tvmaze.com/uploads/images/medium_portrait/1/1.jpg',
                'original' => 'https://static.tvmaze.com/uploads/images/original_untouched/1/1.jpg',
            ],
            'summary' => 'A test show',
            'updated' => 1640995200,
            '_links' => [
                'self' => ['href' => 'https://api.tvmaze.com/shows/1'],
            ],
        ];

        $this->mockHandler->append(new Response(200, [], json_encode($mockResponse)));

        $result = $this->client->getShow(1);

        $this->assertInstanceOf(Show::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test Show', $result->name);
    }

    public function testGetShowEpisodes(): void
    {
        $mockResponse = [
            [
                'id' => 1,
                'url' => 'https://www.tvmaze.com/episodes/1/test-episode',
                'name' => 'Test Episode',
                'season' => 1,
                'number' => 1,
                'type' => 'regular',
                'airdate' => '2010-01-01',
                'airtime' => '21:00',
                'airstamp' => '2010-01-01T21:00:00+00:00',
                'runtime' => 60,
                'rating' => null,
                'image' => null,
                'summary' => 'A test episode',
                '_links' => [
                    'self' => ['href' => 'https://api.tvmaze.com/episodes/1'],
                ],
                'show' => [
                    'id' => 1,
                    'name' => 'Test Show',
                ],
            ],
        ];

        $this->mockHandler->append(new Response(200, [], json_encode($mockResponse)));

        $result = $this->client->getShowEpisodes(1);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Episode::class, $result[0]);
        $this->assertEquals(1, $result[0]->id);
        $this->assertEquals('Test Episode', $result[0]->name);
    }

    public function testGetEpisodeByNumber(): void
    {
        $mockResponse = [
            'id' => 1,
            'url' => 'https://www.tvmaze.com/episodes/1/test-episode',
            'name' => 'Test Episode',
            'season' => 1,
            'number' => 1,
            'type' => 'regular',
            'airdate' => '2010-01-01',
            'airtime' => '21:00',
            'airstamp' => '2010-01-01T21:00:00+00:00',
            'runtime' => 60,
            'rating' => null,
            'image' => null,
            'summary' => 'A test episode',
            '_links' => [
                'self' => ['href' => 'https://api.tvmaze.com/episodes/1'],
            ],
            'show' => [
                'id' => 1,
                'name' => 'Test Show',
                'url' => null,
                'type' => null,
                'language' => null,
                'genres' => [],
                'status' => null,
                'runtime' => null,
                'averageRuntime' => null,
                'premiered' => null,
                'ended' => null,
                'officialSite' => null,
                'schedule' => null,
                'rating' => null,
                'weight' => null,
                'network' => null,
                'webChannel' => null,
                'dvdCountry' => null,
                'externals' => null,
                'image' => null,
                'summary' => null,
                'updated' => null,
                '_links' => null,
            ],
        ];

        $this->mockHandler->append(new Response(200, [], json_encode($mockResponse)));

        $result = $this->client->getEpisodeByNumber(1, 1, 1);

        $this->assertInstanceOf(Episode::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test Episode', $result->name);
        $this->assertEquals(1, $result->season);
        $this->assertEquals(1, $result->number);
    }

    public function testSearchPeople(): void
    {
        $mockResponse = [
            [
                'score' => 0.99,
                'person' => [
                    'id' => 1,
                    'url' => 'https://www.tvmaze.com/people/1/test-person',
                    'name' => 'Test Person',
                    'country' => [
                        'name' => 'United States',
                        'code' => 'US',
                        'timezone' => 'America/New_York',
                    ],
                    'birthday' => '1980-01-01',
                    'deathday' => null,
                    'gender' => 'Male',
                    'image' => [
                        'medium' => 'https://static.tvmaze.com/uploads/images/medium_portrait/1/1.jpg',
                        'original' => 'https://static.tvmaze.com/uploads/images/original_untouched/1/1.jpg',
                    ],
                    'updated' => 1640995200,
                    '_links' => [
                        'self' => ['href' => 'https://api.tvmaze.com/people/1'],
                    ],
                ],
            ],
        ];

        $this->mockHandler->append(new Response(200, [], json_encode($mockResponse)));

        $result = $this->client->searchPeople('test');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals(0.99, $result[0]['score']);
        $this->assertEquals('Test Person', $result[0]['person']['name']);
    }

    public function testGetPerson(): void
    {
        $mockResponse = [
            'id' => 1,
            'url' => 'https://www.tvmaze.com/people/1/test-person',
            'name' => 'Test Person',
            'country' => [
                'name' => 'United States',
                'code' => 'US',
                'timezone' => 'America/New_York',
            ],
            'birthday' => '1980-01-01',
            'deathday' => null,
            'gender' => 'Male',
            'image' => [
                'medium' => 'https://static.tvmaze.com/uploads/images/medium_portrait/1/1.jpg',
                'original' => 'https://static.tvmaze.com/uploads/images/original_untouched/1/1.jpg',
            ],
            'updated' => 1640995200,
            '_links' => [
                'self' => ['href' => 'https://api.tvmaze.com/people/1'],
            ],
        ];

        $this->mockHandler->append(new Response(200, [], json_encode($mockResponse)));

        $result = $this->client->getPerson(1);

        $this->assertInstanceOf(Person::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test Person', $result->name);
    }

    public function testGetSchedule(): void
    {
        $mockResponse = [
            [
                'id' => 1,
                'url' => 'https://www.tvmaze.com/episodes/1/test-episode',
                'name' => 'Test Episode',
                'season' => 1,
                'number' => 1,
                'type' => 'regular',
                'airdate' => '2010-01-01',
                'airtime' => '21:00',
                'airstamp' => '2010-01-01T21:00:00+00:00',
                'runtime' => 60,
                'rating' => null,
                'image' => null,
                'summary' => 'A test episode',
                '_links' => [
                    'self' => ['href' => 'https://api.tvmaze.com/episodes/1'],
                ],
                'show' => [
                    'id' => 1,
                    'name' => 'Test Show',
                ],
            ],
        ];

        $this->mockHandler->append(new Response(200, [], json_encode($mockResponse)));

        $result = $this->client->getSchedule('US', '2010-01-01');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    public function testRateLimitException(): void
    {
        $this->mockHandler->append(new Response(429, [], 'Rate limit exceeded'));

        $this->expectException(RateLimitException::class);
        $this->client->searchShows('test');
    }

    public function testClientException(): void
    {
        $this->mockHandler->append(new Response(400, [], 'Bad Request'));

        $this->expectException(ClientException::class);
        $this->client->searchShows('test');
    }

    public function testServerException(): void
    {
        $this->mockHandler->append(new Response(500, [], 'Internal Server Error'));

        $this->expectException(ServerException::class);
        $this->client->searchShows('test');
    }

    public function testCreateStatic(): void
    {
        $client = TVMazeClient::create('Test-Client/1.0');

        $this->assertInstanceOf(TVMazeClient::class, $client);
    }
}
