<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * Embedded resources model
 */
class Embedded
{
    public function __construct(
        public readonly ?array $episodes,
        public readonly ?array $cast,
        public readonly ?array $crew,
        public readonly ?array $nextepisode,
        public readonly ?array $previousepisode
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            episodes: $data['episodes'] ?? null,
            cast: $data['cast'] ?? null,
            crew: $data['crew'] ?? null,
            nextepisode: $data['nextepisode'] ?? null,
            previousepisode: $data['previousepisode'] ?? null
        );
    }
}
