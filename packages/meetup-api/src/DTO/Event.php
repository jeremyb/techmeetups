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
        $self->description = (string) $data['description'];
        $self->visibility = $data['visibility']; // => string(6) "public"
        $self->venue = isset($data['venue']) ? Venue::fromData($data['venue']) : null;
        $self->group = Group::fromData($data['group']);

        return $self;
    }
}
