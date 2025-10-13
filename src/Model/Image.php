<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * Image model.
 */
class Image
{
    public function __construct(
        public readonly ?string $medium,
        public readonly ?string $original
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            medium: $data['medium'] ?? null,
            original: $data['original'] ?? null
        );
    }
}
