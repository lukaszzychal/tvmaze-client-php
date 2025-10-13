<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * HAL Link model.
 */
class Link
{
    public function __construct(
        public readonly ?string $href
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            href: $data['href'] ?? null
        );
    }
}
