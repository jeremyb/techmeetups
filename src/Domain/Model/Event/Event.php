<?php
declare(strict_types=1);

namespace Domain\Model\Event;

final class Event
{
    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $description;
    /** @var string */
    private $link;
    /** @var int (in minutes) */
    private $duration;
    /** @var \DateTimeImmutable */
    private $plannedAt;
    /** @var null|Venue */
    private $venue;
    /** @var Group */
    private $group;

    public static function named(string $name)
    {
        $self = new self();
        $self->id = strtolower($name);
        $self->name = $name;

        return $self;
    }

    public static function create($id, $name, $description, $link, $duration, \DateTimeImmutable $plannedAt, Venue $venue = null, Group $group)
    {
        $self = new self();
        $self->id = $id;
        $self->name = $name;
        $self->description = $description;
        $self->link = $link;
        $self->duration = $duration;
        $self->plannedAt = $plannedAt;
        $self->venue = $venue;
        $self->group = $group;

        return $self;
    }

    private function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getPlannedAt()
    {
        return $this->plannedAt;
    }

    public function getVenue()
    {
        return $this->venue;
    }

    public function getGroup()
    {
        return $this->group;
    }
}
