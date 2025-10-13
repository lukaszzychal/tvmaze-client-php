<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * Schedule model for shows.
 */
class Schedule
{
    public function __construct(
        public readonly ?string $time,
        public readonly array $days
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            time: $data['time'] ?? null,
            days: $data['days'] ?? []
        );
    }
}
