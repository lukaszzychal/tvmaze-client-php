<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * DVD Country model
 */
class DvdCountry
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $code,
        public readonly ?string $timezone
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            code: $data['code'] ?? null,
            timezone: $data['timezone'] ?? null
        );
    }
}
