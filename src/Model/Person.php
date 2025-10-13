<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * Person model
 */
class Person
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $url,
        public readonly ?string $name,
        public readonly ?Country $country,
        public readonly ?string $birthday,
        public readonly ?string $deathday,
        public readonly ?string $gender,
        public readonly ?Image $image,
        public readonly int $updated,
        public readonly ?Links $_links,
        public readonly ?Embedded $_embedded = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            url: $data['url'] ?? null,
            name: $data['name'] ?? null,
            country: isset($data['country']) ? Country::fromArray($data['country']) : null,
            birthday: $data['birthday'] ?? null,
            deathday: $data['deathday'] ?? null,
            gender: $data['gender'] ?? null,
            image: isset($data['image']) ? Image::fromArray($data['image']) : null,
            updated: $data['updated'],
            _links: isset($data['_links']) ? Links::fromArray($data['_links']) : null,
            _embedded: isset($data['_embedded']) ? Embedded::fromArray($data['_embedded']) : null
        );
    }
}
