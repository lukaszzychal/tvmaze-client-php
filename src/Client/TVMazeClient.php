<?php

declare(strict_types=1);

namespace TVMaze\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TVMaze\Exception\ClientException;
use TVMaze\Exception\RateLimitException;
use TVMaze\Exception\ServerException;
use TVMaze\Exception\TVMazeException;
use TVMaze\Model\Episode;
use TVMaze\Model\Person;
use TVMaze\Model\Show;

/**
 * PSR-18 HTTP client for TVMaze API.
 */
class TVMazeClient implements ClientInterface
{
    private const BASE_URI = 'https://api.tvmaze.com';
    private const USER_AGENT = 'TVMaze-PHP-Client/1.0';

    public function __construct(
        private readonly GuzzleClient $httpClient
    ) {
    }

    public static function create(?string $userAgent = null): self
    {
        $guzzleClient = new GuzzleClient([
            'base_uri' => self::BASE_URI,
            'timeout' => 30,
            'headers' => [
                'User-Agent' => $userAgent ?? self::USER_AGENT,
                'Accept' => 'application/json',
            ],
        ]);

        return new self($guzzleClient);
    }

    /**
     * Search for shows.
     *
     * @param string $query Search query
     * @return array<int, array{score: float, show: Show}>
     * @throws TVMazeException
     */
    public function searchShows(string $query): array
    {
        $response = $this->request('GET', '/search/shows', ['q' => $query]);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return array_map(
            fn (array $item) => [
                'score' => $item['score'],
                'show' => Show::fromArray($item['show']),
            ],
            $data
        );
    }

    /**
     * Single show search.
     *
     * @param string $query Search query
     * @param array<string> $embed Embedded resources
     * @return Show|null
     * @throws TVMazeException
     */
    public function singleShowSearch(string $query, array $embed = []): ?Show
    {
        $params = ['q' => $query];
        if (!empty($embed)) {
            $params['embed'] = $embed;
        }

        try {
            $response = $this->request('GET', '/singlesearch/shows', $params);
            $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

            return Show::fromArray($data);
        } catch (TVMazeException $e) {
            if ($e->getCode() === 404) {
                return null;
            }

            throw $e;
        }
    }

    /**
     * Lookup show by external ID.
     *
     * @param string $type Type of external ID (thetvdb, imdb, tvrage)
     * @param string $id External ID
     * @return Show|null
     * @throws TVMazeException
     */
    public function lookupShow(string $type, string $id): ?Show
    {
        try {
            $response = $this->request('GET', '/lookup/shows', [$type => $id]);
            $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

            return Show::fromArray($data);
        } catch (TVMazeException $e) {
            if ($e->getCode() === 404) {
                return null;
            }

            throw $e;
        }
    }

    /**
     * Get show by ID.
     *
     * @param int $id Show ID
     * @param array<string> $embed Embedded resources
     * @return Show
     * @throws TVMazeException
     */
    public function getShow(int $id, array $embed = []): Show
    {
        $params = [];
        if (!empty($embed)) {
            $params['embed'] = $embed;
        }

        $response = $this->request('GET', "/shows/{$id}", $params);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return Show::fromArray($data);
    }

    /**
     * Get show episodes.
     *
     * @param int $showId Show ID
     * @param bool $includeSpecials Include special episodes
     * @return array<Episode>
     * @throws TVMazeException
     */
    public function getShowEpisodes(int $showId, bool $includeSpecials = false): array
    {
        $params = [];
        if ($includeSpecials) {
            $params['specials'] = '1';
        }

        $response = $this->request('GET', "/shows/{$showId}/episodes", $params);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return array_map(fn (array $episode) => Episode::fromArray($episode), $data);
    }

    /**
     * Get episode by number.
     *
     * @param int $showId Show ID
     * @param int $season Season number
     * @param int $number Episode number
     * @return Episode
     * @throws TVMazeException
     */
    public function getEpisodeByNumber(int $showId, int $season, int $number): Episode
    {
        $response = $this->request('GET', "/shows/{$showId}/episodebynumber", [
            'season' => $season,
            'number' => $number,
        ]);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return Episode::fromArray($data);
    }

    /**
     * Get episodes by date.
     *
     * @param int $showId Show ID
     * @param string $date Date in YYYY-MM-DD format
     * @return array<Episode>
     * @throws TVMazeException
     */
    public function getEpisodesByDate(int $showId, string $date): array
    {
        $response = $this->request('GET', "/shows/{$showId}/episodesbydate", ['date' => $date]);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return array_map(fn (array $episode) => Episode::fromArray($episode), $data);
    }

    /**
     * Get show cast.
     *
     * @param int $showId Show ID
     * @return array
     * @throws TVMazeException
     */
    public function getShowCast(int $showId): array
    {
        $response = $this->request('GET', "/shows/{$showId}/cast");

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get show crew.
     *
     * @param int $showId Show ID
     * @return array
     * @throws TVMazeException
     */
    public function getShowCrew(int $showId): array
    {
        $response = $this->request('GET', "/shows/{$showId}/crew");

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Search for people.
     *
     * @param string $query Search query
     * @return array<int, array{score: float, person: Person}>
     * @throws TVMazeException
     */
    public function searchPeople(string $query): array
    {
        $response = $this->request('GET', '/search/people', ['q' => $query]);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return array_map(
            fn (array $item) => [
                'score' => $item['score'],
                'person' => Person::fromArray($item['person']),
            ],
            $data
        );
    }

    /**
     * Get person by ID.
     *
     * @param int $id Person ID
     * @param array<string> $embed Embedded resources
     * @return Person
     * @throws TVMazeException
     */
    public function getPerson(int $id, array $embed = []): Person
    {
        $params = [];
        if (!empty($embed)) {
            $params['embed'] = $embed;
        }

        $response = $this->request('GET', "/people/{$id}", $params);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return Person::fromArray($data);
    }

    /**
     * Get schedule.
     *
     * @param string|null $country Country code (ISO 3166-1)
     * @param string|null $date Date in YYYY-MM-DD format
     * @return array
     * @throws TVMazeException
     */
    public function getSchedule(?string $country = null, ?string $date = null): array
    {
        $params = [];
        if ($country !== null) {
            $params['country'] = $country;
        }
        if ($date !== null) {
            $params['date'] = $date;
        }

        $response = $this->request('GET', '/schedule', $params);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get web schedule.
     *
     * @param string|null $country Country code (ISO 3166-1) or empty string for global only
     * @param string|null $date Date in YYYY-MM-DD format
     * @return array
     * @throws TVMazeException
     */
    public function getWebSchedule(?string $country = null, ?string $date = null): array
    {
        $params = [];
        if ($country !== null) {
            $params['country'] = $country;
        }
        if ($date !== null) {
            $params['date'] = $date;
        }

        $response = $this->request('GET', '/schedule/web', $params);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get show updates.
     *
     * @param string|null $since Filter by time (day, week, month)
     * @return array
     * @throws TVMazeException
     */
    public function getShowUpdates(?string $since = null): array
    {
        $params = [];
        if ($since !== null) {
            $params['since'] = $since;
        }

        $response = $this->request('GET', '/updates/shows', $params);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get people updates.
     *
     * @param string|null $since Filter by time (day, week, month)
     * @return array
     * @throws TVMazeException
     */
    public function getPeopleUpdates(?string $since = null): array
    {
        $params = [];
        if ($since !== null) {
            $params['since'] = $since;
        }

        $response = $this->request('GET', '/updates/people', $params);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Send HTTP request.
     *
     * @param string $method HTTP method
     * @param string $uri URI
     * @param array $query Query parameters
     * @return string Response body
     * @throws TVMazeException
     */
    private function request(string $method, string $uri, array $query = []): string
    {
        try {
            $options = [];
            if (!empty($query)) {
                $options['query'] = $query;
            }

            $response = $this->httpClient->request($method, $uri, $options);

            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            $this->handleRequestException($e);

            return ''; // This line will never be reached due to exception
        }
    }

    /**
     * Handle request exceptions.
     *
     * @param RequestException $e
     * @throws TVMazeException
     */
    private function handleRequestException(RequestException $e): void
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 0;

        if ($statusCode === 429) {
            throw new RateLimitException('Rate limit exceeded', 429, $e);
        }

        if ($statusCode >= 400 && $statusCode < 500) {
            throw new ClientException(
                "Client error: {$statusCode}",
                $statusCode,
                $e
            );
        }

        if ($statusCode >= 500) {
            throw new ServerException(
                "Server error: {$statusCode}",
                $statusCode,
                $e
            );
        }

        throw new TVMazeException('Request failed', 0, $e);
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->httpClient->send($request);
        } catch (GuzzleException $e) {
            if ($e instanceof RequestException) {
                $this->handleRequestException($e);
            }

            throw new TVMazeException('Request failed', 0, $e);
        }
    }
}
