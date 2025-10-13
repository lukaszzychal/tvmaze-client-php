<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * Rating model.
 */
class Rating
{
    public function __construct(
        public readonly ?float $average
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            average: $data['average'] ?? null
        );
    }
}
