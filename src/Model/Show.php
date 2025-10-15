<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * TV Show model.
 */
class Show
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $url,
        public readonly ?string $name,
        public readonly ?string $type,
        public readonly ?string $language,
        public readonly array $genres,
        public readonly ?string $status,
        public readonly ?int $runtime,
        public readonly ?int $averageRuntime,
        public readonly ?string $premiered,
        public readonly ?string $ended,
        public readonly ?string $officialSite,
        public readonly ?Schedule $schedule,
        public readonly ?Rating $rating,
        public readonly ?int $weight,
        public readonly ?Network $network,
        public readonly ?WebChannel $webChannel,
        public readonly ?DvdCountry $dvdCountry,
        public readonly ?Externals $externals,
        public readonly ?Image $image,
        public readonly ?string $summary,
        public readonly ?int $updated,
        public readonly ?Links $_links,
        public readonly ?Embedded $_embedded = null
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            url: $data['url'] ?? null,
            name: $data['name'] ?? null,
            type: $data['type'] ?? null,
            language: $data['language'] ?? null,
            genres: $data['genres'] ?? [],
            status: $data['status'] ?? null,
            runtime: $data['runtime'] ?? null,
            averageRuntime: $data['averageRuntime'] ?? null,
            premiered: $data['premiered'] ?? null,
            ended: $data['ended'] ?? null,
            officialSite: $data['officialSite'] ?? null,
            schedule: isset($data['schedule']) ? Schedule::fromArray($data['schedule']) : null,
            rating: isset($data['rating']) ? Rating::fromArray($data['rating']) : null,
            weight: $data['weight'] ?? null,
            network: isset($data['network']) ? Network::fromArray($data['network']) : null,
            webChannel: isset($data['webChannel']) ? WebChannel::fromArray($data['webChannel']) : null,
            dvdCountry: isset($data['dvdCountry']) ? DvdCountry::fromArray($data['dvdCountry']) : null,
            externals: isset($data['externals']) ? Externals::fromArray($data['externals']) : null,
            image: isset($data['image']) ? Image::fromArray($data['image']) : null,
            summary: $data['summary'] ?? null,
            updated: $data['updated'] ?? null,
            _links: isset($data['_links']) ? Links::fromArray($data['_links']) : null,
            _embedded: isset($data['_embedded']) ? Embedded::fromArray($data['_embedded']) : null
        );
    }

    /**
     * Get truncated summary text.
     */
    public function getTruncatedSummary(int $maxLength = 200): ?string
    {
        if (!$this->summary) {
            return null;
        }

        $summary = strip_tags($this->summary);

        if (strlen($summary) <= $maxLength) {
            return $summary;
        }

        return substr($summary, 0, $maxLength) . '...';
    }
}
