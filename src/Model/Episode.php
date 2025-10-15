<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * Episode model.
 */
class Episode
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $url,
        public readonly ?string $name,
        public readonly ?int $season,
        public readonly ?int $number,
        public readonly ?string $type,
        public readonly ?string $airdate,
        public readonly ?string $airtime,
        public readonly ?string $airstamp,
        public readonly ?int $runtime,
        public readonly ?Rating $rating,
        public readonly ?Image $image,
        public readonly ?string $summary,
        public readonly ?Links $_links,
        public readonly ?Show $show,
        public readonly ?Embedded $_embedded = null
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            url: $data['url'] ?? null,
            name: $data['name'] ?? null,
            season: $data['season'] ?? null,
            number: $data['number'] ?? null,
            type: $data['type'] ?? null,
            airdate: $data['airdate'] ?? null,
            airtime: $data['airtime'] ?? null,
            airstamp: $data['airstamp'] ?? null,
            runtime: $data['runtime'] ?? null,
            rating: isset($data['rating']) ? Rating::fromArray($data['rating']) : null,
            image: isset($data['image']) ? Image::fromArray($data['image']) : null,
            summary: $data['summary'] ?? null,
            _links: isset($data['_links']) ? Links::fromArray($data['_links']) : null,
            show: isset($data['show']) ? Show::fromArray($data['show']) : null,
            _embedded: isset($data['_embedded']) ? Embedded::fromArray($data['_embedded']) : null
        );
    }

    /**
     * Get formatted episode title as "S{season}E{number}: {name}".
     */
    public function getFormattedTitle(): string
    {
        $season = $this->season ?? '?';
        $number = $this->number ?? '?';
        $name = $this->name ?? 'Unknown';

        return "S{$season}E{$number}: {$name}";
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
