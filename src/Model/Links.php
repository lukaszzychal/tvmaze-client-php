<?php

declare(strict_types=1);

namespace TVMaze\Model;

/**
 * HAL Links model
 */
class Links
{
    public function __construct(
        public readonly ?Link $self,
        public readonly ?Link $previousepisode,
        public readonly ?Link $nextepisode
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            self: isset($data['self']) ? Link::fromArray($data['self']) : null,
            previousepisode: isset($data['previousepisode']) ? Link::fromArray($data['previousepisode']) : null,
            nextepisode: isset($data['nextepisode']) ? Link::fromArray($data['nextepisode']) : null
        );
    }
}
