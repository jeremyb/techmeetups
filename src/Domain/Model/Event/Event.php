<?php

declare(strict_types=1);

namespace Domain\Model\Event;

use Domain\Model\City\City;

final class Event
{
    /** @var EventId */
    private $id;
    /** @var City */
    private $city;
    /** @var string */
    private $name;
    /** @var string */
    private $description;
    /** @var string */
    private $link;
    /** @var int (in minutes) */
    private $duration;
    /** @var \DateTimeImmutable */
    private $createdAt;
    /** @var \DateTimeImmutable */
    private $plannedAt;
    /** @var null|Venue */
    private $venue;
    /** @var Group */
    private $group;

    public static function named(string $name) : self
    {
        $self = new self();
        $self->id = strtolower($name);
        $self->name = $name;

        return $self;
    }

    public static function create(
        EventId $id,
        City $city,
        string $name,
        ?string $description,
        string $link,
        ?int $duration,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $plannedAt,
        Venue $venue = null,
        Group $group
    ) : self {
        $self = new self();
        $self->id = $id;
        $self->city = $city;
        $self->name = $name;
        $self->description = $description;
        $self->link = $link;
        $self->duration = $duration;
        $self->createdAt = $createdAt;
        $self->plannedAt = $plannedAt;
        $self->venue = $venue;
        $self->group = $group;

        return $self;
    }

    private function __construct()
    {
    }

    public function getId() : EventId
    {
        return $this->id;
    }

    public function getCity() : City
    {
        return $this->city;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function getLink() : string
    {
        return $this->link;
    }

    public function getDuration() : ?int
    {
        return $this->duration;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPlannedAt() : \DateTimeImmutable
    {
        return $this->plannedAt;
    }

    public function getVenue() : ?Venue
    {
        return $this->venue;
    }

    public function getGroup() : Group
    {
        return $this->group;
    }
}
