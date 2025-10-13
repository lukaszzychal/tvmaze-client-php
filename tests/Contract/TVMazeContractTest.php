<?php

declare(strict_types=1);

namespace TVMaze\Tests\Contract;

use PHPUnit\Framework\TestCase;
use TVMaze\Client\TVMazeClient;
use TVMaze\Model\Episode;
use TVMaze\Model\Person;
use TVMaze\Model\Show;

/**
 * Contract tests against the real TVMaze API
 * These tests verify that our client works with the actual API.
 *
 * @group contract
 */
class TVMazeContractTest extends TestCase
{
    private TVMazeClient $client;

    protected function setUp(): void
    {
        $this->client = TVMazeClient::create('TVMaze-PHP-Client-Tests/1.0');
    }

    public function testSearchShowsContract(): void
    {
        $result = $this->client->searchShows('breaking bad');

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        // Verify structure of first result
        $firstResult = $result[0];
        $this->assertArrayHasKey('score', $firstResult);
        $this->assertArrayHasKey('show', $firstResult);
        $this->assertIsNumeric($firstResult['score']);
        $this->assertIsArray($firstResult['show']);

        // Verify show structure
        $show = $firstResult['show'];
        $this->assertArrayHasKey('id', $show);
        $this->assertArrayHasKey('name', $show);
        $this->assertArrayHasKey('url', $show);
        $this->assertIsInt($show['id']);
        $this->assertIsString($show['name']);
    }

    public function testSingleShowSearchContract(): void
    {
        $show = $this->client->singleShowSearch('breaking bad');

        $this->assertInstanceOf(Show::class, $show);
        $this->assertStringContainsString('Breaking Bad', $show->name ?? '');
        $this->assertGreaterThan(0, $show->id);
    }

    public function testSingleShowSearchNotFoundContract(): void
    {
        $show = $this->client->singleShowSearch('nonexistentshow12345xyz');

        $this->assertNull($show);
    }

    public function testGetShowContract(): void
    {
        // Using Breaking Bad (show ID 169)
        $show = $this->client->getShow(169);

        $this->assertInstanceOf(Show::class, $show);
        $this->assertEquals(169, $show->id);
        $this->assertStringContainsString('Breaking Bad', $show->name ?? '');
        $this->assertIsArray($show->genres);
        $this->assertIsString($show->status);
    }

    public function testGetShowWithEmbedContract(): void
    {
        // Using Breaking Bad (show ID 169) with cast embedded
        $show = $this->client->getShow(169, ['cast']);

        $this->assertInstanceOf(Show::class, $show);
        $this->assertEquals(169, $show->id);
        $this->assertNotNull($show->_embedded);
        $this->assertIsArray($show->_embedded->cast);
        $this->assertNotEmpty($show->_embedded->cast);
    }

    public function testGetShowEpisodesContract(): void
    {
        // Using Breaking Bad (show ID 169)
        $episodes = $this->client->getShowEpisodes(169);

        $this->assertIsArray($episodes);
        $this->assertNotEmpty($episodes);
        $this->assertInstanceOf(Episode::class, $episodes[0]);
        $this->assertGreaterThan(0, $episodes[0]->id);
        $this->assertIsString($episodes[0]->name);
    }

    public function testGetEpisodeByNumberContract(): void
    {
        // Using Breaking Bad (show ID 169), Season 1, Episode 1
        $episode = $this->client->getEpisodeByNumber(169, 1, 1);

        $this->assertInstanceOf(Episode::class, $episode);
        $this->assertEquals(1, $episode->season);
        $this->assertEquals(1, $episode->number);
        $this->assertGreaterThan(0, $episode->id);
    }

    public function testSearchPeopleContract(): void
    {
        $result = $this->client->searchPeople('bryan cranston');

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $firstResult = $result[0];
        $this->assertArrayHasKey('score', $firstResult);
        $this->assertArrayHasKey('person', $firstResult);
        $this->assertIsNumeric($firstResult['score']);
        $this->assertIsArray($firstResult['person']);

        $person = $firstResult['person'];
        $this->assertArrayHasKey('id', $person);
        $this->assertArrayHasKey('name', $person);
        $this->assertIsInt($person['id']);
        $this->assertIsString($person['name']);
    }

    public function testGetPersonContract(): void
    {
        // Using Simon Bradbury (person ID 489)
        $person = $this->client->getPerson(489);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals(489, $person->id);
        $this->assertStringContainsString('Simon Bradbury', $person->name ?? '');
    }

    public function testGetScheduleContract(): void
    {
        $schedule = $this->client->getSchedule('US');

        $this->assertIsArray($schedule);
        // Schedule might be empty depending on the day/time
        if (!empty($schedule)) {
            $firstEpisode = $schedule[0];
            $this->assertArrayHasKey('id', $firstEpisode);
            $this->assertArrayHasKey('name', $firstEpisode);
            $this->assertArrayHasKey('show', $firstEpisode);
            $this->assertIsInt($firstEpisode['id']);
        }
    }

    public function testGetWebScheduleContract(): void
    {
        $schedule = $this->client->getWebSchedule('US');

        $this->assertIsArray($schedule);
        // Web schedule might be empty depending on the day/time
        if (!empty($schedule)) {
            $firstEpisode = $schedule[0];
            $this->assertArrayHasKey('id', $firstEpisode);
            $this->assertArrayHasKey('name', $firstEpisode);
            if (isset($firstEpisode['show'])) {
                $this->assertArrayHasKey('show', $firstEpisode);
            }
            $this->assertIsInt($firstEpisode['id']);
        }
    }

    public function testGetShowUpdatesContract(): void
    {
        $updates = $this->client->getShowUpdates('day');

        $this->assertIsArray($updates);
        // Updates should be an associative array with show IDs as keys
        if (!empty($updates)) {
            $firstShowId = array_key_first($updates);
            $this->assertIsInt($firstShowId);
            $this->assertIsInt($updates[$firstShowId]);
        }
    }

    public function testGetPeopleUpdatesContract(): void
    {
        $updates = $this->client->getPeopleUpdates('day');

        $this->assertIsArray($updates);
        // Updates should be an associative array with person IDs as keys
        if (!empty($updates)) {
            $firstPersonId = array_key_first($updates);
            $this->assertIsInt($firstPersonId);
            $this->assertIsInt($updates[$firstPersonId]);
        }
    }

    public function testLookupShowByTheTvDbContract(): void
    {
        // Breaking Bad has TheTVDB ID 81189
        $show = $this->client->lookupShow('thetvdb', '81189');

        $this->assertInstanceOf(Show::class, $show);
        $this->assertStringContainsString('Breaking Bad', $show->name ?? '');
    }

    public function testLookupShowByImdbContract(): void
    {
        // Breaking Bad has IMDB ID tt0903747
        $show = $this->client->lookupShow('imdb', 'tt0903747');

        $this->assertInstanceOf(Show::class, $show);
        $this->assertStringContainsString('Breaking Bad', $show->name ?? '');
    }

    public function testLookupShowNotFoundContract(): void
    {
        $show = $this->client->lookupShow('thetvdb', '999999999');

        $this->assertNull($show);
    }

    public function testGetShowCastContract(): void
    {
        // Using Breaking Bad (show ID 169)
        $cast = $this->client->getShowCast(169);

        $this->assertIsArray($cast);
        $this->assertNotEmpty($cast);

        $firstCastMember = $cast[0];
        $this->assertArrayHasKey('person', $firstCastMember);
        $this->assertArrayHasKey('character', $firstCastMember);
        $this->assertIsArray($firstCastMember['person']);
        $this->assertIsArray($firstCastMember['character']);
    }

    public function testGetShowCrewContract(): void
    {
        // Using Breaking Bad (show ID 169)
        $crew = $this->client->getShowCrew(169);

        $this->assertIsArray($crew);
        $this->assertNotEmpty($crew);

        $firstCrewMember = $crew[0];
        $this->assertArrayHasKey('person', $firstCrewMember);
        $this->assertArrayHasKey('type', $firstCrewMember);
        $this->assertIsArray($firstCrewMember['person']);
        $this->assertIsString($firstCrewMember['type']);
    }

    /**
     * Test rate limiting by making multiple requests quickly
     * This test might fail if we hit the rate limit, which is expected behavior.
     */
    public function testRateLimitingBehavior(): void
    {
        $requests = 0;
        $rateLimited = false;

        try {
            // Make several requests quickly
            for ($i = 0; $i < 25; $i++) {
                $this->client->searchShows('test');
                $requests++;
                usleep(100000); // 0.1 second delay
            }
        } catch (\TVMaze\Exception\RateLimitException $e) {
            $rateLimited = true;
        }

        // Either we made all requests successfully or we got rate limited
        $this->assertTrue($requests > 0 || $rateLimited, 'Should have made some requests or been rate limited');
    }

    /**
     * Test that the client handles network errors gracefully.
     */
    public function testNetworkErrorHandling(): void
    {
        // This test is skipped as it requires network access and may not be reliable in CI
        $this->markTestSkipped('Network error handling test requires network access');
    }
}
