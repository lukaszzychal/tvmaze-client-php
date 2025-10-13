<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * Network model
 */
class Network
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name,
        public readonly ?Country $country,
        public readonly ?string $officialSite
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'] ?? null,
            country: isset($data['country']) ? Country::fromArray($data['country']) : null,
            officialSite: $data['officialSite'] ?? null
        );
    }
}
