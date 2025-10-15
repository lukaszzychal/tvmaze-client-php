<?php

declare(strict_types=1);

namespace TVMaze\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TVMaze\Client\TVMazeClient;
use TVMaze\Model\Episode;
use TVMaze\Model\Show;

class AttributionTest extends TestCase
{
    public function testClientAttributionHtml(): void
    {
        $client = TVMazeClient::create('test');
        $attribution = $client->getAttributionHtml();

        $this->assertStringContainsString('Data provided by', $attribution);
        $this->assertStringContainsString('https://www.tvmaze.com', $attribution);
        $this->assertStringContainsString('<a href=', $attribution);
        $this->assertStringContainsString('TVMaze</a>', $attribution);
    }

    public function testClientAttributionText(): void
    {
        $client = TVMazeClient::create('test');
        $attribution = $client->getAttributionText();

        $this->assertEquals('Data provided by TVMaze (https://www.tvmaze.com)', $attribution);
    }

    public function testClientAttributionMarkdown(): void
    {
        $client = TVMazeClient::create('test');
        $attribution = $client->getAttributionMarkdown();

        $this->assertEquals('Data provided by [TVMaze](https://www.tvmaze.com)', $attribution);
    }

    public function testClientDetailedAttributionHtml(): void
    {
        $client = TVMazeClient::create('test');
        $attribution = $client->getDetailedAttributionHtml();

        $this->assertStringContainsString('Data provided by', $attribution);
        $this->assertStringContainsString('https://www.tvmaze.com', $attribution);
        $this->assertStringContainsString('CC BY-SA 4.0', $attribution);
        $this->assertStringContainsString('creativecommons.org', $attribution);
    }

    public function testShowAttributionHtml(): void
    {
        $show = new Show(
            id: 1,
            url: 'https://example.com',
            name: 'Test Show',
            type: 'Scripted',
            language: 'English',
            genres: ['Drama'],
            status: 'Running',
            runtime: 60,
            averageRuntime: 60,
            premiered: '2023-01-01',
            ended: null,
            officialSite: 'https://example.com',
            schedule: null,
            rating: null,
            weight: 1,
            network: null,
            webChannel: null,
            dvdCountry: null,
            externals: null,
            image: null,
            summary: 'Test summary',
            updated: 1234567890,
            _links: null,
            _embedded: null
        );

        $attribution = $show->getAttributionHtml();

        $this->assertStringContainsString('Data provided by', $attribution);
        $this->assertStringContainsString('https://www.tvmaze.com', $attribution);
        $this->assertStringContainsString('<a href=', $attribution);
        $this->assertStringContainsString('TVMaze</a>', $attribution);
    }

    public function testShowAttributionText(): void
    {
        $show = new Show(
            id: 1,
            url: 'https://example.com',
            name: 'Test Show',
            type: 'Scripted',
            language: 'English',
            genres: ['Drama'],
            status: 'Running',
            runtime: 60,
            averageRuntime: 60,
            premiered: '2023-01-01',
            ended: null,
            officialSite: 'https://example.com',
            schedule: null,
            rating: null,
            weight: 1,
            network: null,
            webChannel: null,
            dvdCountry: null,
            externals: null,
            image: null,
            summary: 'Test summary',
            updated: 1234567890,
            _links: null,
            _embedded: null
        );

        $attribution = $show->getAttributionText();

        $this->assertEquals('Data provided by TVMaze (https://www.tvmaze.com)', $attribution);
    }

    public function testShowAttributionMarkdown(): void
    {
        $show = new Show(
            id: 1,
            url: 'https://example.com',
            name: 'Test Show',
            type: 'Scripted',
            language: 'English',
            genres: ['Drama'],
            status: 'Running',
            runtime: 60,
            averageRuntime: 60,
            premiered: '2023-01-01',
            ended: null,
            officialSite: 'https://example.com',
            schedule: null,
            rating: null,
            weight: 1,
            network: null,
            webChannel: null,
            dvdCountry: null,
            externals: null,
            image: null,
            summary: 'Test summary',
            updated: 1234567890,
            _links: null,
            _embedded: null
        );

        $attribution = $show->getAttributionMarkdown();

        $this->assertEquals('Data provided by [TVMaze](https://www.tvmaze.com)', $attribution);
    }

    public function testEpisodeAttributionHtml(): void
    {
        $episode = new Episode(
            id: 1,
            url: 'https://example.com',
            name: 'Test Episode',
            season: 1,
            number: 1,
            type: 'regular',
            airdate: '2023-01-01',
            airtime: '20:00',
            airstamp: '2023-01-01T20:00:00+00:00',
            runtime: 60,
            rating: null,
            image: null,
            summary: 'Test episode summary',
            _links: null,
            show: null,
            _embedded: null
        );

        $attribution = $episode->getAttributionHtml();

        $this->assertStringContainsString('Data provided by', $attribution);
        $this->assertStringContainsString('https://www.tvmaze.com', $attribution);
        $this->assertStringContainsString('<a href=', $attribution);
        $this->assertStringContainsString('TVMaze</a>', $attribution);
    }

    public function testEpisodeAttributionText(): void
    {
        $episode = new Episode(
            id: 1,
            url: 'https://example.com',
            name: 'Test Episode',
            season: 1,
            number: 1,
            type: 'regular',
            airdate: '2023-01-01',
            airtime: '20:00',
            airstamp: '2023-01-01T20:00:00+00:00',
            runtime: 60,
            rating: null,
            image: null,
            summary: 'Test episode summary',
            _links: null,
            show: null,
            _embedded: null
        );

        $attribution = $episode->getAttributionText();

        $this->assertEquals('Data provided by TVMaze (https://www.tvmaze.com)', $attribution);
    }

    public function testEpisodeAttributionMarkdown(): void
    {
        $episode = new Episode(
            id: 1,
            url: 'https://example.com',
            name: 'Test Episode',
            season: 1,
            number: 1,
            type: 'regular',
            airdate: '2023-01-01',
            airtime: '20:00',
            airstamp: '2023-01-01T20:00:00+00:00',
            runtime: 60,
            rating: null,
            image: null,
            summary: 'Test episode summary',
            _links: null,
            show: null,
            _embedded: null
        );

        $attribution = $episode->getAttributionMarkdown();

        $this->assertEquals('Data provided by [TVMaze](https://www.tvmaze.com)', $attribution);
    }
}
