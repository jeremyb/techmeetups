<?php

declare(strict_types=1);

namespace Domain\Model\Event;

final class EventId
{
    /** @var string */
    private $id;

    public static function fromString(string $id) : self
    {
        return new self($id);
    }

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString() : string
    {
        return $this->id;
    }

    public function getId() : string
    {
        return $this->id;
    }
}
