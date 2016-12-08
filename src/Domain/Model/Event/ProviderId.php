<?php

namespace Domain\Model\Event;

final class ProviderId
{
    /** @var string */
    private $id;

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    private function __construct($id)
    {
        $this->id = (string) $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
