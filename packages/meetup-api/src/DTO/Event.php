<?php

declare(strict_types=1);

namespace Meetup\DTO;

use DateTimeImmutable;

final class Event
{
    /** @var string */
    public $id;
    /** @var DateTimeImmutable */
    public $created;
    /** @var int (in minutes) */
    public $duration;
    /** @var string */
    public $name;
    /** @var EventStatus */
    public $status;
    /** @var DateTimeImmutable */
    public $time;
    /** @var DateTimeImmutable */
    public $updated;
    /** @var int (in seconds) */
    public $utcOffset;
    /** @var string */
    public $link;
    /** @var string */
    public $description;
    /** @var string */
    public $visibility;
    /** @var int */
    public $numberOfMembers;
    /** @var int */
    public $limitOfMembers;
    /** @var null|Venue */
    public $venue;
    /** @var Group */
    public $group;

    public static function fromData(array $data)
    {
        $self = new self();
        $self->id = (string) $data['id'];
        $self->created = (new DateTimeImmutable())->setTimestamp($data['created']/1000);
        $self->duration = isset($data['duration']) ? $data['duration']/1000/60 : null;
        $self->name = (string) $data['name'];
        $self->status = new EventStatus($data['status']);
        $self->time = (new DateTimeImmutable())->setTimestamp($data['time']/1000);
        $self->updated = (new DateTimeImmutable())->setTimestamp($data['updated']/1000);
        $self->utcOffset = $data['utc_offset']/1000;
        $self->link = $data['link'];
        $self->description = isset($data['description']) ? (string) $data['description'] : null;
        $self->visibility = $data['visibility'];
        $self->numberOfMembers = (int) ($data['yes_rsvp_count'] ?? 0);
        $self->limitOfMembers = (int) ($data['rsvp_limit'] ?? 0);
        $self->venue = isset($data['venue']) ? Venue::fromData($data['venue']) : null;
        $self->group = Group::fromData($data['group']);

        return $self;
    }
}
