<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * External IDs model
 */
class Externals
{
    public function __construct(
        public readonly ?int $tvrage,
        public readonly ?int $thetvdb,
        public readonly ?string $imdb
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            tvrage: $data['tvrage'] ?? null,
            thetvdb: $data['thetvdb'] ?? null,
            imdb: $data['imdb'] ?? null
        );
    }
}
