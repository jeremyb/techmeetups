<?php

declare(strict_types=1);

namespace Meetup\DTO;

use DateTimeImmutable;
use Meetup\Hydrator\HydratorFactory;

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
    /** @var EventVisibility */
    public $visibility;
    /** @var int */
    public $numberOfMembers;
    /** @var int */
    public $limitOfMembers;
    /** @var null|Venue */
    public $venue;
    /** @var Group */
    public $group;

    public static function fromData(array $data) : self
    {
        /** @var self $event */
        $event = HydratorFactory::create()->hydrate($data, new self());
        $event->visibility = new EventVisibility($data['visibility']);
        $event->status = isset($data['status']) ? new EventStatus($data['status']) : null;
        $event->created = isset($data['created']) ? (new DateTimeImmutable())->setTimestamp($data['created']/1000) : null;
        $event->updated = isset($data['updated']) ? (new DateTimeImmutable())->setTimestamp($data['updated']/1000) : null;
        $event->duration = isset($data['duration']) ? $data['duration']/1000/60 : null;
        $event->time = (new DateTimeImmutable())->setTimestamp($data['time']/1000);
        $event->utcOffset = $data['utc_offset']/1000;
        $event->numberOfMembers = (int) ($data['yes_rsvp_count'] ?? 0);
        $event->limitOfMembers = (int) ($data['rsvp_limit'] ?? 0);
        $event->venue = isset($data['venue']) ? Venue::fromData($data['venue']) : null;
        $event->group = Group::fromData($data['group']);

        return $event;
    }
}
